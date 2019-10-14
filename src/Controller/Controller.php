<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validation;

abstract class Controller extends AbstractController
{
    private $security;
    protected $urlGenerator;
    protected $userRepo;
    protected $user;

    public function __construct(Security $security, UrlGeneratorInterface $urlGenerator)
    {
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
    }

    public function getUserRepository()
    {
        return $this->userRepo = $this->getDoctrine()->getManager()->getRepository(User::class);
    }

    public function getUser()
    {
        return $this->user = $this->security->getUser();
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