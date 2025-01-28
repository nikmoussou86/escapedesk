<?php
declare(strict_types=1);

namespace App\Controllers;


use App\Repositories\UserRepository;
use Psr\Http\Message\ServerRequestInterface;


class UserController
{
    public function __construct(
        private UserRepository $userRepository
    )
    {}

    public function index()
    {}

    public function create(): string
    {
        // Todo: Replace with a proper templating engine.
        return <<<HTML
            <form action="/users/store" method="POST">
                <label for="user_name">Username:</label>
                <input type="text" name="user_name" id="user_name" required>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                <label for="type">Type:</label>
                <input type="number" name="type" id="type" required>
                <button type="submit">Create</button>
            </form>
        HTML;
    }

    public function store(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();

        // Store the user in the database
        $this->userRepository->create($data);
    }

    public function edit(ServerRequestInterface $request, array $params)
    {
        $user = $this->userRepository->findByUserId(intval($params['userId']));

        if ($user) {
            // Todo: Replace with a proper templating engine.
            return <<<HTML
                <form action="/users/update" method="POST">
                    <label for="user_name">Username:</label>
                    <input type="text" name="user_name" id="user_name" value="{$user->getUserName()}" required>
                    
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="{$user->getEmail()}" required>
                    
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                    
                    <label for="type">Type:</label>
                    <input type="number" name="type" id="type" value="{$user->getType()->value}" required>
                    
                    <button type="submit">Update</button>
                </form>
            HTML;
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
        }
        
        return false;
    }
}