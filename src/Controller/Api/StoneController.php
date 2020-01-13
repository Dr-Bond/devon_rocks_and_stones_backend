<?php

namespace App\Controller\Api;

use App\CommandBus\Api\AddStoneCommand;
use App\Controller\Controller;
use App\Entity\Clue;
use App\Entity\Location;
use App\Entity\Stone;
use App\Helper\FileUploader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

class StoneController extends Controller
{
    public function index()
    {
        $array = [
            'stones' => [],
            'error' => false
        ];

        $hiddenLocations = $this->orm->getRepository(Location::class)->findHiddenStonesLocations();

        if(count($hiddenLocations) > 0) {
            foreach ($hiddenLocations as $hiddenLocation) {
                $array['stones'][] = [
                    'id' => $hiddenLocation->getStone()->getId(),
                    'status' => $hiddenLocation->getStone()->getStatus(),
                    'location' => $hiddenLocation->getId(),
                    'area' => $hiddenLocation->getArea(),
                    'findable' => $hiddenLocation->getHiddenBy() !== $this->getPlayer() ? true : false
                ];
            }
            $array['error'] = false;
        }
        return new JsonResponse($array, 200);
    }

    public function found(Stone $stone, Location $location)
    {
        $stone->setStatus(Stone::STATUS_NOT_HIDDEN);
        $location->setKept(true);
        $location->setFoundOn(new \DateTime());
        $location->setFoundBy($this->getPlayer());
        $this->orm->flush();

        return new JsonResponse(['message' => 'Stone marked as found.'], 201);
    }

    public function add(Request $request, MessageBusInterface $bus)
    {
        $location = $request->get('location');
        $player = $this->getPlayer();
        $command = new AddStoneCommand($player, $location);

        if(false !== $error = $this->validatePayload($command)) {
            return $error;
        }

        $bus->dispatch($command);
        return new JsonResponse(["error" => false,"message" => "Stone Hidden"], 201);
    }

    public function clues(Stone $stone, Location $location)
    {
        $array = [
            'clues' => [],
            'error' => false
        ];

        $clues = $this->orm->getRepository(Clue::class)->findBy(['location' => $location]);

        if(count($clues) > 0) {
            foreach ($clues as $clue) {
                $array['clues'][] = [
                    'id' => $clue->getId(),
                    'content' => $clue->getContent(),
                    'addedBy' => $clue->getAddedBy()->getFirstName().' '.$clue->getAddedBy()->getSurname(),
                    'image' => $clue->getImage() !== null ? self::IMAGE_PATH.$clue->getImage() : null,
                    'deletable' => $clue->getAddedBy() === $this->getPlayer() ? true : false
                ];
            }
            $array['error'] = false;
        }
        return new JsonResponse($array, 200);
    }


    public function addClue(Stone $stone, Location $location, Request $request, FileUploader $fileUploader)
    {
        $orm = $this->orm;
        $content = $request->get('content');
        $clue = new Clue($this->getPlayer(),$location,$content);

        $file = $request->files->get('file');
        if ($file) {
            $fileName = $fileUploader->upload($file);
            $clue->setImage($fileName);
        }
        $orm->persist($clue);
        $orm->flush();

        return new JsonResponse(['message' => 'Clue added for stone '.$stone->getId().'!'], 201);
    }

    public function deleteClue(Clue $clue)
    {
        if($clue->getAddedBy() === $this->getPlayer()) {
            $this->orm->remove($clue);
            $this->orm->flush();
            return new JsonResponse([ 'error' => false, 'message' => 'Clue deleted!'], 201);
        };

        return new JsonResponse(['error' => true, 'message' => 'You do not have access!'], 401);
    }


    public function rehide(Stone $stone, Location $location, Request $request)
    {
        $area = $request->get('newLocation');
        $stone->setStatus(Stone::STATUS_HIDDEN);
        $location->setKept(false);
        $location->setFoundOn(new \DateTime());
        $location->setFoundBy($this->getPlayer());
        $newLocation = new Location($this->getPlayer(),$stone,$area);
        $newLocation->setPreviousLocation($location);
        $this->orm->persist($newLocation);
        $this->orm->flush();

        return new JsonResponse(['message' => 'Stone rehidden!.'], 201);
    }
}