<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use Psr\Http\Message\ServerRequestInterface;

class AuthController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
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

    public function login(ServerRequestInterface $request): string
    {
        $data = $request->getParsedBody();

        if ($this->authService->authenticate($data['email'], $data['password'])) {
            // Redirect or display success message.
            return "Login successful! <a href='/'>Go to home</a>";
        }

        http_response_code(401);
        return "Invalid credentials. <a href='/login'>Try again</a>";
    }

    public function logout(): string
    {
        $this->authService->logout();
        return "You have been logged out. <a href='/'>Go to home</a>";
    }
}
