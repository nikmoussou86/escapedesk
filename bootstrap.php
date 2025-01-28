<?php
declare(strict_types=1);

use Twig\Environment;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManager;
use Twig\Loader\FilesystemLoader;
use App\Controllers\AuthController;
use App\Repositories\UserRepository;
use Psr\Container\ContainerInterface;
use App\Repositories\BcryptPasswordHasher;
use App\Repositories\NativeSessionManager;
use App\Repositories\Contracts\PasswordHasherInterface;
use App\Repositories\Contracts\SessionManagerInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions([
    // Setup PDO
    PDO::class => function () {
        return new PDO("mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}", $_ENV['DB_USER'], $_ENV['DB_PASS']);
    },
    // Doctrine EntityManager
    EntityManager::class => function () {
        return require 'doctrine.php';
    },
    UserRepositoryInterface::class => function (ContainerInterface $c) {
        return new UserRepository($c->get(EntityManager::class));
    },
    // Session manager
    SessionManagerInterface::class => function () {
        return new NativeSessionManager();
    },
    // Password hasher
    PasswordHasherInterface::class => function () {
        return new BcryptPasswordHasher();
    },
    // Twig templating engine
    Environment::class => function () {
        $loader = new FilesystemLoader(__DIR__ . '/views');
        return new Environment($loader);
    },
    // Authentication controller
    AuthController::class => function (ContainerInterface $c) {
        return new AuthController(
            $c->get(UserRepositoryInterface::class),
            $c->get(PasswordHasherInterface::class),
            $c->get(NativeSessionManager::class),
            $c->get(Environment::class)
        );
    },
]);


return $containerBuilder->build();
