<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 */
class Event
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string")
	 */
	private $name;

	/**
	 * @ORM\Column(type="string")
	 */
	private $slug;

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
	private $sound;

	/**
	 * @ORM\ManyToMany(targetEntity="Game")
	 */
	private $games;

	public function __construct()
	{
		$this->games = new ArrayCollection();
	}

	public function getId()
	{
		return $this->id;
	}

	public function getSlug()
	{
		return $this->slug;
	}

	public function setSlug($value)
	{
		return $this->slug = $value;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($value)
	{
		return $this->name = $value;
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

	public function getSound()
	{
		return $this->sound;
	}

	public function setSound($value)
	{
		return $this->sound = $value;
	}

	public function getGames()
	{
		return $this->games;
	}

	public function edit($values)
	{
		if (!empty($values['name'])) {
			$this->setName($values['name']);
		}
		if (!empty($values['description'])) {
			$this->setDescription($values['description']);
		}	
		if (!empty($values['image'])) {
			$this->setImage($values['image']);
		}
		if (!empty($values['sound'])) {
			$this->setSound($values['sound']);
		}
		if (!empty($values['slug'])) {
			$this->setSlug($values['slug']);
		}					
	}
}