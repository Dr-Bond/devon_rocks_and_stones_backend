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

    public function active()
    {
        $results = $this->orm->getRepository(Player::class)->loginActivity();
        $headings = "'Weeks', ";
        $data = "'Last Active', ";
        foreach ($results as $result) {
            $heading = $result['number_of_weeks'];
            $users = $result['number_of_users'];
            $headings .= "'$heading',";
            $data .= "$users,";
        }
        $headings .= " { role: 'annotation' } ";
        $data = "[".$headings."],[".rtrim($data,',').",'']";
        dump($data); exit;
        return $this->render('web/player/active.html.twig',['data' => $data]);
    }

    public function weeklyPoints()
    {
        $points = $this->orm->getRepository(Player::class)->weeklyPoints();
        return $this->render('web/player/weekly_points.html.twig',['points' => $points]);
    }

}
