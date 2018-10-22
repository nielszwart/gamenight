<?php

// namespace App\Entity;

// use Doctrine\ORM\Mapping as ORM;
// use App\Entity\EventPlayer;

// /**
//  * @ORM\Entity()
//  */
// class Score
// {
// 	/**
// 	 * @ORM\Id
// 	 * @ORM\Column(type="integer")
// 	 * @ORM\GeneratedValue(strategy="AUTO")
// 	 */
// 	private $id;

// 	/**
// 	 * @ORM\ManyToOne(targetEntity="EventPlayer")
// 	 */
// 	private $event_player;

// 	/**
// 	 * @ORM\Column(type="integer")
// 	 */
// 	private $main_game_score;

// 	/**
// 	 * @ORM\Column(type="integer", nullable=true)
// 	 */
// 	private $side_game_score;

// 	/**
// 	 * @ORM\Column(type="integer")
// 	 */
// 	private $total_score;

// 		/**
// 	 * @ORM\Column(type="integer", nullable=true)
// 	 */
// 	private $number_of_g;

// 	public function __construct($eventPlayer, $mainGameScore, $sideGameScore)
// 	{
// 		$this->setEventPlayer($eventPlayer);
// 		$this->setMainGameScore($mainGameScore);
// 		$this->setSideGameScore($sideGameScore);
// 		$this->calculateTotalScore();
// 	}

// 	public function getId()
// 	{
// 		return $this->id;
// 	}

// 	public function setEventPlayer(EventPlayer $value)
// 	{
// 		$this->event_player = $value;
// 	}

// 	public function getEventPlayer()
// 	{
// 		return $this->event_player;
// 	}

// 	public function setMainGameScore($value)
// 	{
// 		$this->main_game_score = $value;
// 	}

// 	public function getMainGameScore()
// 	{
// 		return $this->main_game_score;
// 	}

// 	public function setSideGameScore($value)
// 	{
// 		$this->side_game_score = $value;
// 	}

// 	public function getSideGameScore()
// 	{
// 		return $this->side_game_score;
// 	}

// 	public function setTotalScore($value)
// 	{
// 		$this->total_score = $value;
// 	}

// 	public function getTotalScore($value)
// 	{
// 		return $this->total_score;
// 	}

// 	public function calculateTotalScore()
// 	{
// 		$total = (int) $this->getMainGameScore + (int) $this->getSideGameScore();
// 		$this->setTotalScore($score);
// 	}

// 	public function edit($values)
// 	{
// 		if (!empty($values['name'])) {
// 			$this->setName($values['name']);
// 		}
// 		if (!empty($values['nickname'])) {
// 			$this->setNickname($values['nickname']);
// 		}	
// 		if (!empty($values['email'])) {
// 			$this->setEmail($values['email']);
// 		}					
// 	}
// }