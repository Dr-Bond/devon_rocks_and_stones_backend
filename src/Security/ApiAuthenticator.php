<?php

namespace App\Security;

use App\Entity\User;
use App\Helper\Orm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Serializer\SerializerInterface;

class ApiAuthenticator extends AbstractGuardAuthenticator
{
    use TargetPathTrait;

    private $orm;
    private $passwordEncoder;
    private $serializer;

    public function __construct(Orm $orm, UserPasswordEncoderInterface $passwordEncoder, SerializerInterface $serializer)
    {
        $this->orm = $orm;
        $this->passwordEncoder = $passwordEncoder;
        $this->serializer = $serializer;
    }

    public function supports(Request $request)
    {
        return $request->getContent() or $request->headers->has('X-AUTH-TOKEN');
    }

    public function getCredentials(Request $request)
    {
        if($request->getContent()) {
            $user = $this->serializer->deserialize($request->getContent(), \App\Model\User::class, 'json');
            if(!$user->getEmail() and !$user->getPassword()) {
                throw new CustomUserMessageAuthenticationException('Email and password cannot be blank.');
            }
            if(!$user->getEmail()) {
                throw new CustomUserMessageAuthenticationException('Email cannot be blank.');
            }
            if(!$user->getPassword()) {
                throw new CustomUserMessageAuthenticationException('Password cannot be blank.');
            }
            $credentials = [
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
            ];
        } else {
            if(null === $token = $request->headers->get('X-AUTH-TOKEN')) {
                throw new CustomUserMessageAuthenticationException('No credentials found.');
            }
            $credentials = [
                'token' => $token,
            ];
        }
        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if(isset($credentials['email'])) {
            $user = $this->orm->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);
        } else {
            $user = $this->orm->getRepository(User::class)->findOneBy(['apiToken' => $credentials['token']]);
        }

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('User could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if(!isset($credentials['token'])) {
            return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
        }

        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {

        $user = $token->getUser();
        if (!$request->headers->get('X-AUTH-TOKEN')) {
            $user->createApiToken();
            $this->orm->flush();
        }

        $content = [
            'user' => $user->getEmail(),
            'apiToken' => $user->getApiToken()
        ];

        return new JsonResponse($content, Response::HTTP_OK);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'error-message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'error-message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
