<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Entity\User;
use App\Repositories\Contracts\SessionHandlerInterface;


class SessionHandler implements SessionHandlerInterface {
    private const SESSION_USER_KEY = 'user_id';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Start session if not already started
        }
    }

    public function set(string $key, $value): void {
        $_SESSION[$key] = $value;
    }

    public function get(string $key) {
        return $_SESSION[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function destroy(): void
    {
        // Destroy the session to log out the user.
        session_destroy();
    }

    public function userIsLoggedIn(): bool {
        return isset($_SESSION[self::SESSION_USER_KEY]);
    }
}