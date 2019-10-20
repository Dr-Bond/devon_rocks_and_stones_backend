<?php

namespace App\CommandBus\Api;


use App\Entity\Player;
use App\Model\Location;

class AddStoneCommand
{
    private $player;
    private $area;

    public function __construct(Player $player, Location $location)
    {
        $this->player = $player;
        $this->area = $location->getArea();
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getArea(): string
    {
        return $this->area;
    }
}