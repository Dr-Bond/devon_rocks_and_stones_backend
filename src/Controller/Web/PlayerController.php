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

    public function activity()
    {
        $results = $this->orm->getRepository(Player::class)->loginActivity();
        $headings = "'Weeks', ";
        $data = "'Activity', ";
        foreach ($results as $result) {
            $heading = $result['number_of_weeks'];
            $users = $result['number_of_users'];
            $headings .= "'$heading',";
            $data .= "$users,";
        }
        $headings .= " { role: 'annotation' } ";
        $data = "[".$headings."],[".rtrim($data,',').",'']";
        return $this->render('web/player/activity.html.twig',['data' => $data]);
    }

    public function weeklyPoints()
    {
        $points = $this->orm->getRepository(Player::class)->weeklyPoints();
        return $this->render('web/player/weekly_points.html.twig',['points' => $points]);
    }

}
