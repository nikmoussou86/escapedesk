<?php
declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Entity\User;

interface UserRepositoryInterface {
    public function create(array $data): void;
    public function update(array $data): void;
    public function delete(User $user): void;
    public function findByCredentials(string $email, string $password): ?User;
}