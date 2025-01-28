<?php
declare(strict_types=1);

namespace App\Repositories\Contracts;

interface SessionManagerInterface {
    public function start(): void;
    public function set(string $key, $value): void;
    public function get(string $key);
    public function regenerate(): void;
    public function destroy(): void;
}