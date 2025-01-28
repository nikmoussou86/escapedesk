<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use App\Repositories\Contracts\PasswordHasherInterface;
use App\Repositories\Contracts\SessionManagerInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class AuthController
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
        private SessionManagerInterface $sessionManager,
    )
    {
    }

    public function showLoginForm(): string
    {
        // Render a simple login form (this can be replaced with a proper templating engine).
        return <<<HTML
            <form action="/login" method="POST">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                <button type="submit">Login</button>
            </form>
        HTML;
    }

    public function login(ServerRequestInterface $request): bool
    {
        $data = $request->getParsedBody();

        // Find the user by email
        $user = $this->userRepository->findByCredentials($data['email'], $data['password']);

        if (!$user) {
            return false; // User not found
        }
        
        // Verify the password
        if (!$this->passwordHasher->verify($data['password'], $user->getPassword())) {
            return false;
        }

        // Start the session and store user data
        $this->sessionManager->start();
        $this->sessionManager->set('user_id', $user->getId());
        $this->sessionManager->regenerate();        

        return true;
    }

    public function logout(): string
    {
        $this->sessionManager->destroy();
        return "You have been logged out.";
    }
}
