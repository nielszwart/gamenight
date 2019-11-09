<?php

namespace App\Controller;

use App\Controller\BaseController;
use App\Entity\Event;
use App\Entity\EventPlayer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class InviteController extends BaseController
{
    /**
     * @Route("/invite/{event}", name="event_invite")
     */
    public function invite($event, Request $request, UrlGeneratorInterface $url)
    {
    	$code = $request->query->get('code');

		$currentPlayer = $this->getDoctrine()->getRepository(EventPlayer::class)->findBy(['code' => $code]);
		$currentPlayer = !empty($currentPlayer[0]) ? $currentPlayer[0] : null;	

		$justConfirmed = false;
		if ($request->isMethod('POST')) {
			$data = $request->request->all();
			if (!empty($data['confirmed'])) {
				$player = $this->getDoctrine()->getRepository(EventPlayer::class)->find($data['player']);
				$player->setConfirmed();
				$this->save($player);
				$justConfirmed = true;
			}
		}

    	$event = $this->getDoctrine()->getRepository(Event::class)->findOneBy(['slug' => $event]);
    	if (empty($event)) {
    		$this->addFlash('error', 'Could not find event with given slug');
    		return new RedirectResponse($url->generate('homepage'));
    	}

    	$players = $this->getDoctrine()->getRepository(EventPlayer::class)->findBy([
            'event' => $event->getId(),
            'active' => true,
        ]);

        return $this->render('invite/invite.twig', [
        	'event' => $event,
        	'players' => $players,
        	'currentPlayer' => $currentPlayer,
        	'justConfirmed' => $justConfirmed,
        ]);
    }
}
