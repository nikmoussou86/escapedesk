<?php
declare(strict_types=1);

namespace App\Controllers;

use Twig\Environment;
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
        private Environment $twig,
    )
    {
    }

    public function showLoginForm(): string
    {
        // Render the login form
        return $this->twig->render('auth/login.twig');
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
