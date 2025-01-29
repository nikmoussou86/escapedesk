<?php
declare(strict_types=1);

namespace App\Repositories\Contracts;

interface SessionHandlerInterface {
    public function set(string $key, $value): void;
    public function get(string $key);
    public function has(string $key);
    public function destroy(): void;
}