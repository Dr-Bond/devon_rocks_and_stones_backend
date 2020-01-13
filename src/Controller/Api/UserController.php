<?php

namespace App\Controller\Api;

use App\CommandBus\Api\CreateUserCommand;
use App\Controller\Controller;
use App\Entity\Post;
use App\Helper\FileUploader;
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

    public function register(Request $request, MessageBusInterface $bus)
    {
        $params = $request->request->all();
        $user = new \App\Model\User($params);
        $player = new \App\Model\Player($params);

        $command = new CreateUserCommand($this->orm, $user, $player);

        if(false !== $errors = $this->validatePayload($command)) {
            return new JsonResponse(['error' => 1], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $bus->dispatch($command);

        return new JsonResponse(['message' => $command->getEmail(). ' has been registered!'], 201);
    }

    public function login()
    {
        $content = [
            'error' => 'Method not allowed'
        ];

        return new JsonResponse($content, Response::HTTP_BAD_REQUEST);
    }

    public function posts()
    {
        $array = [
            'clues' => [],
            'error' => false
        ];

        $posts = $this->orm->getRepository(Post::class)->findAll();

        if(count($posts) > 0) {
            foreach ($posts as $post) {
                $array['posts'][] = [
                    'id' => $post->getId(),
                    'content' => $post->getContent(),
                    'postedBy' => $post->getAddedBy()->getFirstName().' '.$post->getAddedBy()->getSurname(),
                    'image' => $post->getImage() !== null ? self::IMAGE_PATH.$post->getImage() : null,
                    'deletable' => $post->getAddedBy() === $this->getPlayer() ? true : false
                ];
            }
            $array['error'] = false;
        }
        return new JsonResponse($array, 200);
    }

    public function addPost(Request $request, FileUploader $fileUploader)
    {
        $orm = $this->orm;
        $content = $request->get('content');
        $post = new Post($this->getPlayer(),$content);

        $file = $request->files->get('file');
        if ($file) {
            $fileName = $fileUploader->upload($file);
            $post->setImage($fileName);
        }
        $orm->persist($post);
        $orm->flush();
        return new JsonResponse(['message' => 'Post added!'], 201);
    }

    public function deletePost(Post $post)
    {
        if($post->getAddedBy() === $this->getPlayer()) {
            $this->orm->remove($post);
            $this->orm->flush();
            return new JsonResponse([ 'error' => false, 'message' => 'Post deleted!'], 201);
        };

        return new JsonResponse(['error' => true, 'message' => 'You do not have access!'], 401);
    }
}
