<?php

namespace App\Controller;

use App\Entity\Player;
use App\Helper\Orm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validation;

abstract class Controller extends AbstractController
{
    const IMAGE_PATH = "http://devon-rocks-and-stones.192.168.1.15.xip.io:8888/uploads/stone_directory/";
    //const IMAGE_PATH = "http://devon-rocks-and-stones.192.168.43.3.xip.io:8888/uploads/stone_directory/";

    private $security;
    protected $urlGenerator;
    protected $orm;
    protected $user;

    public function __construct(Security $security, UrlGeneratorInterface $urlGenerator, Orm $orm)
    {
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
        $this->orm = $orm;
    }

    public function getUser()
    {
        return $this->user = $this->security->getUser();
    }

    public function getPlayer()
    {
        return $this->orm->getRepository(Player::class)->findPlayerByUser($this->security->getUser());
    }

    protected function validatePayload($payload)
    {
        $validator = Validation::createValidatorBuilder()
            ->addMethodMapping('loadValidatorMetadata')
            ->getValidator();

        $violations = $validator->validate($payload);

        $errors = [];
        foreach($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();

        }

        if ($violations->count() > 0) {
            return new JsonResponse(["errors" => $errors], 500);
        }

        return false;
    }
}