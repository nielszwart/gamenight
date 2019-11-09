<?php 

namespace App\Service;

use App\Entity\Result;
use App\Entity\Event;
use App\Entity\Game;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ScoringService
{
	protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->doctrine = $container->get('doctrine');
    }

    public function calculateScores(Event $event)
    {
    	$gameScores = ['totals' => []];

    	foreach ($event->getGames() as $game) {
	    	switch ($game->getSlug()) {
	    		case 'mario-kart':
	    			$gameScores[$game->getSlug()] =$this->calculateScoresMarioKart($event, $game);
	    			break;
				case '8bit-fiesta':
					$gameScores[$game->getSlug()] = $this->calculateScores8BitFiesta($event, $game);
					break;
    		}
    	}

    	foreach ($gameScores as $scores) {
    		foreach ($scores as $playerId => $score) {
    			if (!isset($gameScores['totals'][$playerId])) {
    				$gameScores['totals'][$playerId] = [
    					'score' => 0,
    					'nickname' => $score['nickname'],
    				];
    			}
				$gameScores['totals'][$playerId]['score'] += $score['score'];
    		}
    	}

    	$gameScores['totals'] = $this->sortByScore($gameScores['totals']);

    	return $gameScores;
    }

	public function calculateScoresMarioKart(Event $event, Game $game)
	{
		$results = $this->doctrine->getRepository(Result::class)->findBy([
			'event' => $event->getId(),
			'game' => $game->getId(),
		]);

		$scores = [];
		foreach ($results as $result) {
			$scores = $this->setPlayers($scores, $result);
			if (!empty($result->getThird()) && !empty($result->getFourth())) {
				$scores[$result->getFirst()->getPlayer()->getId()]['points'] += 80;
				$scores[$result->getFirst()->getPlayer()->getId()]['games']++;
				$scores[$result->getSecond()->getPlayer()->getId()]['points'] += 50;
				$scores[$result->getSecond()->getPlayer()->getId()]['games']++;
				$scores[$result->getThird()->getPlayer()->getId()]['points'] += 30;
				$scores[$result->getThird()->getPlayer()->getId()]['games']++;
				$scores[$result->getFourth()->getPlayer()->getId()]['points'] += 0;
				$scores[$result->getFourth()->getPlayer()->getId()]['games']++;
			} elseif (!empty($result->getThird()) && empty($result->getFourth())) {
				$scores = $this->setPlayers($scores, $result);
				$scores[$result->getFirst()->getPlayer()->getId()]['points'] += 80;
				$scores[$result->getFirst()->getPlayer()->getId()]['games']++;
				$scores[$result->getSecond()->getPlayer()->getId()]['points'] += 50;
				$scores[$result->getSecond()->getPlayer()->getId()]['games']++;
				$scores[$result->getThird()->getPlayer()->getId()]['points'] += 0;
				$scores[$result->getThird()->getPlayer()->getId()]['games']++;
			} elseif (empty($result->getThird()) && empty($result->getFourth())) {
				$scores = $this->setPlayers($scores, $result);
				$scores[$result->getFirst()->getPlayer()->getId()]['points'] += 80;
				$scores[$result->getFirst()->getPlayer()->getId()]['games']++;
				$scores[$result->getSecond()->getPlayer()->getId()]['points'] += 30;
				$scores[$result->getSecond()->getPlayer()->getId()]['games']++;
			}
		}

		foreach ($scores as &$score) {
			$score['score'] = round($score['points'] / $score['games']);
		}
		unset($score);

		$scores = $this->sortByScore($scores);

		return $scores;
	}

	public function calculateScores8BitFiesta(Event $event, Game $game)
	{
		$results = $this->doctrine->getRepository(Result::class)->findBy([
			'event' => $event->getId(),
			'game' => $game->getId(),
		]);

		$scores = [];
		foreach ($results as $result) {
			$scores = $this->setPlayers($scores, $result);

			if ($scores[$result->getFirst()->getPlayer()->getId()]['points'] < 20) {
				$scores[$result->getFirst()->getPlayer()->getId()]['points'] += 4;
			}

			$scores[$result->getFirst()->getPlayer()->getId()]['games']++;
			$scores[$result->getSecond()->getPlayer()->getId()]['games']++;
			if (!empty($result->getThird())) {
				$scores[$result->getThird()->getPlayer()->getId()]['games']++;
			}
			if (!empty($result->getFourth())) {
				$scores[$result->getFourth()->getPlayer()->getId()]['games']++;
			}
		}

		foreach ($scores as &$score) {
			$score['score'] = $score['points'];
		}
		unset($score);

		$scores = $this->sortByScore($scores);

		return $scores;
	}

	public function calculateScoresPuyoPuyo(Event $event, Game $game) 
	{
		$results = $this->doctrine->getRepository(Result::class)->findBy([
			'event' => $event->getId(),
			'game' => $game->getId(),
		]);

		$scores = [];
		foreach ($results as $result) {
			$scores = $this->setPlayers($scores, $result);
			if (!empty($result->getThird()) && !empty($result->getFourth())) {
				$scores[$result->getFirst()->getPlayer()->getId()]['points'] += 80;
				$scores[$result->getFirst()->getPlayer()->getId()]['games']++;
				$scores[$result->getSecond()->getPlayer()->getId()]['points'] += 50;
				$scores[$result->getSecond()->getPlayer()->getId()]['games']++;
				$scores[$result->getThird()->getPlayer()->getId()]['points'] += 30;
				$scores[$result->getThird()->getPlayer()->getId()]['games']++;
				$scores[$result->getFourth()->getPlayer()->getId()]['points'] += 0;
				$scores[$result->getFourth()->getPlayer()->getId()]['games']++;
			} elseif (!empty($result->getThird()) && empty($result->getFourth())) {
				$scores = $this->setPlayers($scores, $result);
				$scores[$result->getFirst()->getPlayer()->getId()]['points'] += 80;
				$scores[$result->getFirst()->getPlayer()->getId()]['games']++;
				$scores[$result->getSecond()->getPlayer()->getId()]['points'] += 50;
				$scores[$result->getSecond()->getPlayer()->getId()]['games']++;
				$scores[$result->getThird()->getPlayer()->getId()]['points'] += 0;
				$scores[$result->getThird()->getPlayer()->getId()]['games']++;
			} elseif (empty($result->getThird()) && empty($result->getFourth())) {
				$scores = $this->setPlayers($scores, $result);
				$scores[$result->getFirst()->getPlayer()->getId()]['points'] += 80;
				$scores[$result->getFirst()->getPlayer()->getId()]['games']++;
				$scores[$result->getSecond()->getPlayer()->getId()]['points'] += 30;
				$scores[$result->getSecond()->getPlayer()->getId()]['games']++;
			}
		}

		foreach ($scores as &$score) {
			$score['score'] = round($score['points'] / $score['games']);
		}
		unset($score);

		$scores = $this->sortByScore($scores);

		return $scores;		
	}


	protected function setPlayers($scores, Result $result)
	{
		if (!isset($scores[$result->getFirst()->getPlayer()->getId()])) {
			$scores[$result->getFirst()->getPlayer()->getId()] = $this->newPlayerArray($result->getFirst()->getPlayer()->getNickname());
		}
		if (!empty($result->getSecond()) && !isset($scores[$result->getSecond()->getPlayer()->getId()])) {
			$scores[$result->getSecond()->getPlayer()->getId()] = $this->newPlayerArray($result->getSecond()->getPlayer()->getNickname());
		}
		if (!empty($result->getThird()) && !isset($scores[$result->getThird()->getPlayer()->getId()])) {
			$scores[$result->getThird()->getPlayer()->getId()] = $this->newPlayerArray($result->getThird()->getPlayer()->getNickname());
		}
		if (!empty($result->getFourth()) && !isset($scores[$result->getFourth()->getPlayer()->getId()])) {
			$scores[$result->getFourth()->getPlayer()->getId()] = $this->newPlayerArray($result->getFourth()->getPlayer()->getNickname());
		}

		return $scores;
	}

	protected function newPlayerArray($nickname)
	{
		return [
			'nickname' => $nickname,
			'points' => 0,
			'games' => 0,
		];
	}

	protected function sortByScore($scores)
	{
		uasort($scores, function($a, $b) {
		    return $b['score'] <=> $a['score'];
		});

		return $scores;
	}
}