<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;

class AuthService
{
    public function authenticate(string $email, string $password): bool
    {
        // Fetch the user by email (simulating a database check).
        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user->getPassword())) {
            // Store user in session.
            $_SESSION['user'] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
            ];
            return true;
        }

        return false;
    }

    public function logout(): void
    {
        // Destroy the session to log out the user.
        session_destroy();
    }

    public function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }
}
