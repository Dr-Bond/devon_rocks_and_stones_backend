<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PublicController extends AbstractController
{
    public function index()
    {
        return $this->render('public/index.html.twig');
    }
}