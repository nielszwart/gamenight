<?php

namespace App\Controller;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends BaseController
{
    public function homepage()
    {
        return $this->render('website/homepage.twig', ['whatever' => 'hoi']);
    }
}