<?php

namespace App\Controller\Api;

use App\CommandBus\Api\CreateUserCommand;
use App\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends Controller
{
    public function index()
    {
        $user = $this->getUser();

        $content = [
            'email' => $user->getUsername()
        ];

        return new JsonResponse($content, 200);
    }

    public function register(Request $request, SerializerInterface $serializer, MessageBusInterface $bus)
    {

        $user = $serializer->deserialize($request->getContent(), \App\Model\User::class, 'json');
        $player = $serializer->deserialize($request->getContent(), \App\Model\Player::class, 'json');

        $command = new CreateUserCommand($user, $player);

        if(false !== $error = $this->validatePayload($command)) {
            return $error;
        }
        
        $bus->dispatch($command);

        return new JsonResponse(["success" => $command->getEmail(). " has been registered!"], 200);
    }

    public function login()
    {
        $content = [
            'error-message' => 'Method not allowed'
        ];

        return new JsonResponse($content, Response::HTTP_BAD_REQUEST);
    }
}
