<?php

namespace App\Controller;

use App\Entity\User;
use App\Helper\Orm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validation;

abstract class Controller extends AbstractController
{
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