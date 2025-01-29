<?php
declare(strict_types=1);

use Twig\Environment;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManager;
use Twig\Loader\FilesystemLoader;
use App\Controllers\AuthController;
use App\Controllers\BaseController;
use App\Controllers\UserController;
use App\Repositories\SessionHandler;
use App\Repositories\UserRepository;
use Psr\Container\ContainerInterface;
use App\Repositories\BcryptPasswordHasher;
use App\Controllers\VacationRequestController;
use App\Repositories\VacationRequestRepository;
use App\Repositories\Contracts\PasswordHasherInterface;
use App\Repositories\Contracts\SessionHandlerInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\VacationRequestRepositoryInterface;

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions([
    // Doctrine EntityManager
    EntityManager::class => function () {
        return require 'doctrine.php';
    },
    UserRepositoryInterface::class => function (ContainerInterface $c) {
        return new UserRepository($c->get(EntityManager::class));
    },
    VacationRequestRepositoryInterface::class => function (ContainerInterface $c) {
        return new VacationRequestRepository($c->get(EntityManager::class));
    },
    // Session manager
    SessionHandlerInterface::class => function () {
        return new SessionHandler();
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
    BaseController::class => function(ContainerInterface $c) {
        return new BaseController(
            $c->get(SessionHandlerInterface::class),
            $c->get(UserRepositoryInterface::class),
        );
    },
    // Authentication controller
    AuthController::class => function (ContainerInterface $c) {
        return new AuthController(
            $c->get(SessionHandlerInterface::class),
            $c->get(UserRepositoryInterface::class),
            $c->get(PasswordHasherInterface::class),
            $c->get(Environment::class)
        );
    },
    UserController::class => function(ContainerInterface $c) {
        return new UserController(
            $c->get(SessionHandlerInterface::class),
            $c->get(UserRepositoryInterface::class),
            $c->get(Environment::class),
        );
    },
    VacationRequestController::class => function(ContainerInterface $c) {
        return new VacationRequestController(
            $c->get(SessionHandlerInterface::class),
            $c->get(VacationRequestRepositoryInterface::class),
            $c->get(UserRepositoryInterface::class),
            $c->get(Environment::class),
        );
    }
]);


return $containerBuilder->build();
