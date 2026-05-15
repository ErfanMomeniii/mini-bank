<?php

declare(strict_types=1);

use ErfanMomeniii\MiniBank\Controller\AccountController;
use ErfanMomeniii\MiniBank\Controller\CurrencyController;
use ErfanMomeniii\MiniBank\Controller\TransactionController;
use ErfanMomeniii\MiniBank\Controller\UserController;
use Slim\App;

return function (App $app): void {
    // Users
    $app->get('/users', [UserController::class, 'index']);
    $app->post('/users', [UserController::class, 'create']);
    $app->get('/users/{id}', [UserController::class, 'show']);
    $app->put('/users/{id}', [UserController::class, 'update']);
    $app->delete('/users/{id}', [UserController::class, 'destroy']);

    // Currencies
    $app->get('/currencies', [CurrencyController::class, 'index']);
    $app->post('/currencies', [CurrencyController::class, 'create']);
    $app->get('/currencies/{id}', [CurrencyController::class, 'show']);
    $app->put('/currencies/{id}', [CurrencyController::class, 'update']);
    $app->delete('/currencies/{id}', [CurrencyController::class, 'destroy']);

    // Accounts
    $app->get('/accounts', [AccountController::class, 'index']);
    $app->post('/accounts', [AccountController::class, 'create']);
    $app->get('/accounts/{id}', [AccountController::class, 'show']);
    $app->put('/accounts/{id}', [AccountController::class, 'update']);
    $app->delete('/accounts/{id}', [AccountController::class, 'destroy']);

    // Transactions
    $app->get('/transactions', [TransactionController::class, 'index']);
    $app->post('/transactions', [TransactionController::class, 'create']);
    $app->get('/transactions/{id}', [TransactionController::class, 'show']);
};
