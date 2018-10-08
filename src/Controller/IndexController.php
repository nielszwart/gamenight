<?php

namespace App\Controller;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Event;

class IndexController extends BaseController
{
    public function homepage()
    {
      return $this->render(
      	'website/homepage.twig', [
      		'whatever' => 'hoi'
  	    ]
    	);
    }
}