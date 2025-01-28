<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Contracts\PasswordHasherInterface;

class BcryptPasswordHasher implements PasswordHasherInterface {
    public function verify(string $password, string $hashedPassword): bool {
        return password_verify($password, $hashedPassword);
    }
}