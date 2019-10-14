<?php

namespace App\Controller\Web;

use App\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('web/user/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    public function logout()
    {
    }
}
