<?php

namespace App\Model;

class Location
{
    private $id;
    private $stone;
    private $area;

    public function getId(): int
    {
        return $this->id;
    }

    public function getStone(): int
    {
        return $this->stone;
    }

    public function setStone($stone): self
    {
        $this->stone = $stone;
        return $this;
    }

    public function getArea(): string
    {
        return $this->area;
    }

    public function setArea(string $area): self
    {
        $this->area = $area;
        return $this;
    }
}