<?php

namespace App\Controller\Web;

use App\Controller\Controller;
use App\Entity\Player;

class PlayerController extends Controller
{
    public function index()
    {
        $players = $this->orm->getRepository(Player::class)->findAll();
        return $this->render('web/player/index.html.twig',['players' => $players]);
    }

}
