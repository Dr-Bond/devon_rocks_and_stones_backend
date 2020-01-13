<?php

namespace App\CommandBus\Api;

use App\Entity\Player;

class AddStoneCommand
{
    private $player;
    private $location;

    public function __construct(Player $player, $location)
    {
        $this->player = $player;
        $this->location = $location;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getLocation(): string
    {
        return $this->location;
    }
}