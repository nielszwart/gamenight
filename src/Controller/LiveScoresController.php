<?php

namespace App\Controller;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Event;
use App\Entity\Result;
use App\Service\ScoringService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class LiveScoresController extends BaseController
{
	/**
     * @Route("/{event}/livescores", name="livescores")
     */
    public function livescores($event, ScoringService $scoring)
    {
    	$event = $this->getDoctrine()->getRepository(Event::class)->findOneBy(['slug' => $event]);
    	if (empty($event)) {
    		$this->addFlash('error', 'Could not find event with given slug');
    		return new RedirectResponse($url->generate('homepage'));
    	}

    	$scores = $scoring->calculateScores($event);

		return $this->render(
			'event/livescores.twig', 
			[
				'event' => $event,
				'scores' => $scores,
			]
		);
    }

    /**
     * @Route("/{event}/get-scores", name="getscores")
     */
    public function getscores($event, ScoringService $scoring)
    {
        $slug = $event;
        $event = $this->getDoctrine()->getRepository(Event::class)->findOneBy(['slug' => $slug]);
        if (empty($event)) {
            return new JsonResponse('Could not find event: '. $slug, 404);
        }

        $scores = $scoring->calculateScores($event);

        $scores = $this->testChanges(false, $scores);

        $response = [
            'totals' => array_values($scores['totals'])
        ];
        unset($scores['totals']);
        foreach ($scores as $game => $score) {
            $response[] = array_merge(['slug' => $game], $score);
        }

        return new JsonResponse($response);
    }

    public function testChanges($execute, $scores)
    {
        if ($execute) {
            $scores['totals'][] = [
                'score' => rand(0,50),
                'nickname' => 'Naomi',
            ];

            $scores['totals'][1]['score'] = rand(0,50);
            $x = $scores['totals'];
            uasort($x, function($a, $b) {
                return $b['score'] <=> $a['score'];
            });
            $scores['totals'] = $x;

            $scores['mario-kart'][] = [
                'score' => rand(0,50),
                'nickname' => 'Naomi',
                'points' => rand(0,500),
                'games' => rand(0,10),
            ];

            $scores['mario-kart'][1]['score'] = rand(0,50);
            $scores['mario-kart'][1]['points'] = rand(0,500);
            $scores['mario-kart'][1]['games'] = rand(0,10);
            $x = $scores['mario-kart'];
            uasort($x, function($a, $b) {
                return $b['score'] <=> $a['score'];
            });
            $scores['mario-kart'] = $x;

            $scores['8bit-fiesta'][1]['score'] = rand(0,4);
            $scores['8bit-fiesta'][1]['points'] = rand(0,20);
            $scores['8bit-fiesta'][1]['games'] = rand(0,10);
            $x = $scores['8bit-fiesta'];
            uasort($x, function($a, $b) {
                return $b['score'] <=> $a['score'];
            });
            $scores['8bit-fiesta'] = $x;
        }

        return $scores;
    }
}