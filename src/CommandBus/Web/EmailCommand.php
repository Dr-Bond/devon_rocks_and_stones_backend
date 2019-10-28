<?php

namespace App\CommandBus\Web;

use App\Entity\Player;

class EmailCommand
{
    private $player;
    private $content;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): EmailCommand
    {
        $this->player = $player;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content): EmailCommand
    {
        $this->content = $content;
        return $this;
    }
}