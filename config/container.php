<?php

declare(strict_types=1);

use ErfanMomeniii\MiniBank\Action\Auth\LoginAction;
use ErfanMomeniii\MiniBank\Domain\Repository\AccountRepositoryInterface;
use ErfanMomeniii\MiniBank\Domain\Repository\CurrencyRepositoryInterface;
use ErfanMomeniii\MiniBank\Domain\Repository\TransactionRepositoryInterface;
use ErfanMomeniii\MiniBank\Domain\Repository\UserRepositoryInterface;
use ErfanMomeniii\MiniBank\Infrastructure\Http\Middleware\AuthMiddleware;
use ErfanMomeniii\MiniBank\Infrastructure\Persistence\AccountRepository;
use ErfanMomeniii\MiniBank\Infrastructure\Persistence\Connection;
use ErfanMomeniii\MiniBank\Infrastructure\Persistence\CurrencyRepository;
use ErfanMomeniii\MiniBank\Infrastructure\Persistence\TransactionRepository;
use ErfanMomeniii\MiniBank\Infrastructure\Persistence\UserRepository;
use ErfanMomeniii\MiniBank\Responder\JsonResponder;
use Psr\Container\ContainerInterface;
use function DI\autowire;
use function DI\factory;

return [
    'settings' => fn() => require __DIR__ . '/settings.php',

    PDO::class => factory(fn() => Connection::getInstance()),

    CurrencyRepositoryInterface::class => autowire(CurrencyRepository::class),
    UserRepositoryInterface::class => autowire(UserRepository::class),

    AccountRepositoryInterface::class => factory(
        fn(ContainerInterface $c) => new AccountRepository(
            $c->get(PDO::class),
            $c->get(CurrencyRepositoryInterface::class),
        )
    ),

    TransactionRepositoryInterface::class => factory(
        fn(ContainerInterface $c) => new TransactionRepository(
            $c->get(PDO::class),
            $c->get(CurrencyRepositoryInterface::class),
        )
    ),

    AuthMiddleware::class => factory(
        fn(ContainerInterface $c) => new AuthMiddleware(
            $c->get('settings')['jwt_key'],
        )
    ),

    LoginAction::class => factory(
        fn(ContainerInterface $c) => new LoginAction(
            $c->get(UserRepositoryInterface::class),
            $c->get(JsonResponder::class),
            $c->get('settings')['jwt_key'],
        )
    ),
];
