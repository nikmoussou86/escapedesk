<?php
declare(strict_types=1);

namespace App\Repositories\Contracts;

interface PasswordHasherInterface {
    public function verify(string $password, string $hashedPassword): bool;
}