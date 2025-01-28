<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Contracts\SessionManagerInterface;

class NativeSessionManager implements SessionManagerInterface {
    public function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function set(string $key, $value): void {
        $_SESSION[$key] = $value;
    }

    public function get(string $key) {
        return $_SESSION[$key] ?? null;
    }

    public function regenerate(): void {
        session_regenerate_id(true);
    }

    public function destroy(): void
    {
        // Destroy the session to log out the user.
        session_destroy();
    }
}