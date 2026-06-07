<?php

declare(strict_types=1);

use DI\Container;
use ErfanMomeniii\MiniBank\Controller\AccountController;
use ErfanMomeniii\MiniBank\Controller\CurrencyController;
use ErfanMomeniii\MiniBank\Controller\TransactionController;
use ErfanMomeniii\MiniBank\Controller\UserController;
use ErfanMomeniii\MiniBank\Database\Connection;
use ErfanMomeniii\MiniBank\Repository\AccountRepositoryInterface;
use ErfanMomeniii\MiniBank\Repository\CurrencyRepositoryInterface;
use ErfanMomeniii\MiniBank\Repository\PostgreSQL\AccountRepository;
use ErfanMomeniii\MiniBank\Repository\PostgreSQL\CurrencyRepository;
use ErfanMomeniii\MiniBank\Repository\PostgreSQL\TransactionRepository;
use ErfanMomeniii\MiniBank\Repository\PostgreSQL\UserRepository;
use ErfanMomeniii\MiniBank\Repository\TransactionRepositoryInterface;
use ErfanMomeniii\MiniBank\Repository\UserRepositoryInterface;
use ErfanMomeniii\MiniBank\Service\AccountService;
use ErfanMomeniii\MiniBank\Service\CurrencyService;
use ErfanMomeniii\MiniBank\Service\TransactionService;
use ErfanMomeniii\MiniBank\Service\UserService;
use function DI\autowire;
use function DI\factory;

return [
    PDO::class => factory(
        fn() => Connection::getInstance()
    ),

    CurrencyRepositoryInterface::class => autowire(CurrencyRepository::class),
    UserRepositoryInterface::class => autowire(UserRepository::class),

    AccountRepositoryInterface::class => factory(
        fn(Container $c) => new AccountRepository(
            $c->get(PDO::class),
            $c->get(CurrencyRepositoryInterface::class),
        )
    ),

    TransactionRepositoryInterface::class => factory(
        fn(Container $c) => new TransactionRepository(
            $c->get(PDO::class),
            $c->get(CurrencyRepositoryInterface::class),
        )
    ),

    CurrencyService::class => autowire(),
    UserService::class => autowire(),
    AccountService::class => autowire(),
    TransactionService::class => autowire(),

    UserController::class => autowire(),
    CurrencyController::class => autowire(),
    AccountController::class => autowire(),
    TransactionController::class => autowire(),
];
