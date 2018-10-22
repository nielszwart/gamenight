<?php

namespace App\Controller;

use App\Controller\BaseController;
use App\Entity\Event;
use App\Entity\EventPlayer;
use App\Entity\Game;
use App\Entity\Result;
use App\Form\ResultType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResultController extends BaseController
{
    /**
     * @Route("/{event}/add-result", name="add_result")
     */
    public function add($event, Request $request, UrlGeneratorInterface $url)
    {
    	$event = $this->getDoctrine()->getRepository(Event::class)->findOneBy(['slug' => $event]);
    	if (empty($event)) {
    		$this->addFlash('error', 'Could not find event with given slug');
    		return new RedirectResponse($url->generate('homepage'));
    	}

    	$players = $this->getDoctrine()->getRepository(EventPlayer::class)->findBy([
            'event' => $event->getId(),
            'active' => true,
            'confirmed' => true,
        ]);
    	$playerChoices = ['Pick a player' => ''];
    	foreach ($players as $player) {
    		$playerChoices[$player->getPlayer()->getNickName()] = $player->getId();
    	}

    	$gameChoices = ['Pick a game' => ''];
    	foreach ($event->getGames() as $game) {
    		$gameChoices[$game->getName()] = $game->getId();
    	}

        $form = $this->createForm(ResultType::class, null, ['data' => [
            'players' => $playerChoices,
            'games' => $gameChoices,
        ]]);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();
                $game = $this->getDoctrine()->getRepository(Game::class)->find($data['game']);
                $first = $this->getDoctrine()->getRepository(EventPlayer::class)->find($data['first']);
                $second = $this->getDoctrine()->getRepository(EventPlayer::class)->find($data['second']);
                $third = null;
                if (!empty($data['third'])) {
					$third = $this->getDoctrine()->getRepository(EventPlayer::class)->find($data['third']);
                }
                $fourth = null;
                if (!empty($data['fourth'])) {
                	$fourth = $this->getDoctrine()->getRepository(EventPlayer::class)->find($data['fourth']);
                }

                $result = new Result($event, $game, $first, $second, $third, $fourth);
				$this->save($result);

				$this->addFlash('success', 'Result saved!');
				return new RedirectResponse($url->generate('add_result', ['event' => $event->getSlug()]));
            } else {
                $this->addFlash('error', 'Some of the fields have invalid input');
            }
        }

        return $this->render(
            'event/add-result.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}