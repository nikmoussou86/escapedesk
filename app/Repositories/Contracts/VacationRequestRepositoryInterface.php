<?php
declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Entity\VacationRequest;

interface VacationRequestRepositoryInterface {
    public function create(array $data): void;
    public function update(array $data): void;
    public function delete(VacationRequest $user): void;
}