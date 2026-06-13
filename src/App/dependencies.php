<?php

declare(strict_types=1);

use DI\Container;
use ErfanMomeniii\MiniBank\Database\Connection;
use ErfanMomeniii\MiniBank\Repository\AccountRepositoryInterface;
use ErfanMomeniii\MiniBank\Repository\CurrencyRepositoryInterface;
use ErfanMomeniii\MiniBank\Repository\PostgreSQL\AccountRepository;
use ErfanMomeniii\MiniBank\Repository\PostgreSQL\CurrencyRepository;
use ErfanMomeniii\MiniBank\Repository\PostgreSQL\TransactionRepository;
use ErfanMomeniii\MiniBank\Repository\PostgreSQL\UserRepository;
use ErfanMomeniii\MiniBank\Repository\TransactionRepositoryInterface;
use ErfanMomeniii\MiniBank\Repository\UserRepositoryInterface;
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
];
