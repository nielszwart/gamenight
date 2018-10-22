<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\EventPlayer;
use App\Entity\Event;
use App\Entity\Game;

/**
 * @ORM\Entity()
 */
class Result
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Event")
	 */
	private $event;

	/**
	 * @ORM\ManyToOne(targetEntity="Game")
	 */
	private $game;

	/**
	 * @ORM\ManyToOne(targetEntity="EventPlayer")
	 */
	private $first;

	/**
	 * @ORM\ManyToOne(targetEntity="EventPlayer")
	 */
	private $second;

	/**
	 * @ORM\ManyToOne(targetEntity="EventPlayer")
	 */
	private $third;

	/**
	 * @ORM\ManyToOne(targetEntity="EventPlayer")
	 */
	private $fourth;

	public function __construct($event, $game, $first, $second, $third = null, $fourth = null)
	{
		$this->setEvent($event);
		$this->setGame($game);
		$this->setFirst($first);
		$this->setSecond($second);
		if ($third) {
			$this->setThird($third);
		}
		if ($fourth) {
			$this->setFourth($fourth);
		}
	}

	public function getId() {return $this->id;}
	public function getEvent() {return $this->event;}
	public function getGame() {return $this->game;}
	public function getFirst() {return $this->first;}
	public function getSecond() {return $this->second;}
	public function getThird() {return $this->third;}
	public function getFourth() {return $this->fourth;}

	public function setEvent(Event $value) {$this->event = $value;}
	public function setGame(Game $value) {$this->game = $value;}
	public function setFirst(EventPlayer $value) {$this->first = $value;}
	public function setSecond(EventPlayer $value) {$this->second = $value;}
	public function setThird(EventPlayer $value) {$this->third = $value;}
	public function setFourth(EventPlayer $value) {$this->fourth = $value;}
}
