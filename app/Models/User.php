<?php

declare(strict_types=1);

namespace App\Models;

class User
{
    private int $id;
    private string $email;
    private string $password;

    public function __construct(int $id, string $email, string $password)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public static function findByEmail(string $email): ?User
    {
        // Simulating a database lookup.
        $users = [
            ['id' => 1, 'email' => 'user@example.com', 'password' => password_hash('password123', PASSWORD_BCRYPT)],
        ];

        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return new User($user['id'], $user['email'], $user['password']);
            }
        }

        return null;
    }
}
