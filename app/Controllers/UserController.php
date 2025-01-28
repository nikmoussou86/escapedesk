<?php
declare(strict_types=1);

namespace App\Controllers;


use Twig\Environment;
use App\Enums\UserType;
use App\Repositories\UserRepository;
use Psr\Http\Message\ServerRequestInterface;


class UserController
{
    public function __construct(
        private UserRepository $userRepository,
        private Environment $twig,
    )
    {}

    public function index()
    {
        $users = $this->userRepository->findAll();

        return $this->twig->render('users/index.twig', [
            'users' => $users,
            'userType' => UserType::class,
        ]);
    }

    public function create(): string
    {
        return $this->twig->render('users/create.twig');
    }

    public function store(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();

        // Store the user in the database
        $this->userRepository->create($data);

        header('Location: /users');
        exit;
    }

    public function edit(ServerRequestInterface $request, array $params)
    {
        $user = $this->userRepository->findByUserId(intval($params['userId']));

        if ($user) {
            return $this->twig->render('users/edit.twig', [
                'user' => $user
            ]);
        }
    }


    public function update(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();

        // Update the user in the database
        $this->userRepository->update($data);
    }

    public function delete(ServerRequestInterface $request, array $params)
    {
        $user = $this->userRepository->findByUserId(intval($params['userId']));

        if ($user) {
            $this->userRepository->delete($user);
            
            header('Location: /users');
            exit;
        }
        return false;
    }
}