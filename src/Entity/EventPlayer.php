<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Event;
use App\Entity\Player;

/**
 * @ORM\Entity()
 */
class EventPlayer
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
	 * @ORM\ManyToOne(targetEntity="Player")
	 */
	private $player;	

	/**
	 * @ORM\Column(type="text")
	 */
	private $description;

	/**
	 * @ORM\Column(type="string")
	 */
	private $image;

	/**
	 * @ORM\Column(type="string")
	 */
	private $code;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $confirmed = false;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $invite_sent = false;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $active = true;

	public function getId()
	{
		return $this->id;
	}

	public function getEvent()
	{
		return $this->event;
	}

	public function setEvent(Event $value)
	{
		return $this->event = $value;
	}

	public function getPlayer()
	{
		return $this->player;
	}

	public function setPlayer(Player $value)
	{
		return $this->player = $value;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription($value)
	{
		return $this->description = $value;
	}

	public function getImage()
	{
		return $this->image;
	}

	public function setImage($value)
	{
		return $this->image = $value;
	}

	public function getCode()
	{
		return $this->code;
	}

	public function setCode()
	{
		return $this->code = md5($this->getId() . $this->getPlayer()->getId() . $this->getEvent()->getId());
	}

	public function getConfirmed()
	{
		return $this->confirmed;
	}

	public function setConfirmed()
	{
		return $this->confirmed = true;
	}	

	public function getInviteSent()
	{
		return $this->invite_sent;
	}

	public function setInviteSent()
	{
		return $this->invite_sent = true;
	}	

	public function getActive()
	{
		return $this->active;
	}

	public function setActive()
	{
		return $this->active = true;
	}

	public function edit($values)
	{
		if (!empty($values['event'])) {
			$this->setEvent($values['event']);
		}
		if (!empty($values['player'])) {
			$this->setPlayer($values['player']);
		}	
		if (!empty($values['description'])) {
			$this->setDescription($values['description']);
		}	
		if (!empty($values['image'])) {
			$this->setImage($values['image']);
		}	
		if (!empty($values['code'])) {
			$this->setCode($values['code']);
		}	
	}
}