<?php

namespace App\Controller\Api;

use App\CommandBus\Api\AddStoneCommand;
use App\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

class StoneController extends Controller
{
    public function add(Request $request, SerializerInterface $serializer, MessageBusInterface $bus)
    {
       $location = $serializer->deserialize($request->getContent(), \App\Model\Location::class, 'json');

        $player = $this->getPlayer();

        $command = new AddStoneCommand($player, $location);

        if(false !== $error = $this->validatePayload($command)) {
            return $error;
        }

        $bus->dispatch($command);

        return new JsonResponse(["success" => "Stone Hidden"], 200);
    }
}