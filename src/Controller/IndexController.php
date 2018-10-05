<?php

namespace App\Controller;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Event;

class IndexController extends BaseController
{
    public function homepage()
    {
    	$event = $this->getDoctrine()->getRepository(Event::class)->findOneBy(['slug' => 'mario-kart']);

   		echo "<pre>";
   		var_dump($event->getName());
   		exit;

        return $this->render(
        	'website/homepage.twig', [
        		'whatever' => 'hoi'
    	    ]
    	);
    }
}