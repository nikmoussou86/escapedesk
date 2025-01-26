<?php
declare(strict_types=1);

use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder();

// Optionally, add definitions for dependencies here.
// Example:
// $containerBuilder->addDefinitions([
//     'SomeService' => DI\autowire(App\Services\SomeService::class),
// ]);

return $containerBuilder->build();
