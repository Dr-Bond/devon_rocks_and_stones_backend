<?php

namespace App\CommandBus\Api;


use App\Entity\Location;
use App\Entity\Stone;
use App\Helper\Orm;

class AddStoneCommandHandler
{
    private $orm;

    public function __construct(Orm $orm)
    {
        $this->orm = $orm;
    }

    public function __invoke(AddStoneCommand $command)
    {
        $orm = $this->orm;
        $stone = new Stone($command->getPlayer());
        $orm->persist($stone);
        $location = new Location(
            $command->getPlayer(),
            $stone,
            $command->getArea()
        );
        $orm->persist($location);
        $orm->flush();
    }
}