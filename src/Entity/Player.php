<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Player
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
	private $nickname;

		/**
	 * @ORM\Column(type="string")
	 */
	private $email;

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($value)
	{
		return $this->name = $value;
	}

	public function getNickname()
	{
		return $this->nickname;
	}

	public function setNickname($value)
	{
		return $this->nickname = $value;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($value)
	{
		return $this->email = $value;
	}

	public function edit($values)
	{
		if (!empty($values['name'])) {
			$this->setName($values['name']);
		}
		if (!empty($values['nickname'])) {
			$this->setNickname($values['nickname']);
		}	
		if (!empty($values['email'])) {
			$this->setEmail($values['email']);
		}					
	}
}