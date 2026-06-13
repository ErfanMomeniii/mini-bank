<?php

declare(strict_types=1);

use ErfanMomeniii\MiniBank\Action\Account\CreateAccountAction;
use ErfanMomeniii\MiniBank\Action\Account\DeleteAccountAction;
use ErfanMomeniii\MiniBank\Action\Account\GetAccountAction;
use ErfanMomeniii\MiniBank\Action\Account\ListAccountsAction;
use ErfanMomeniii\MiniBank\Action\Account\UpdateAccountAction;
use ErfanMomeniii\MiniBank\Action\Currency\CreateCurrencyAction;
use ErfanMomeniii\MiniBank\Action\Currency\DeleteCurrencyAction;
use ErfanMomeniii\MiniBank\Action\Currency\GetCurrencyAction;
use ErfanMomeniii\MiniBank\Action\Currency\ListCurrenciesAction;
use ErfanMomeniii\MiniBank\Action\Currency\UpdateCurrencyAction;
use ErfanMomeniii\MiniBank\Action\Transaction\CreateTransactionAction;
use ErfanMomeniii\MiniBank\Action\Transaction\GetTransactionAction;
use ErfanMomeniii\MiniBank\Action\Transaction\ListTransactionsAction;
use ErfanMomeniii\MiniBank\Action\User\CreateUserAction;
use ErfanMomeniii\MiniBank\Action\User\DeleteUserAction;
use ErfanMomeniii\MiniBank\Action\User\GetUserAction;
use ErfanMomeniii\MiniBank\Action\User\ListUsersAction;
use ErfanMomeniii\MiniBank\Action\User\UpdateUserAction;
use Slim\App;

return function (App $app): void {
    $app->get('/users', ListUsersAction::class);
    $app->post('/users', CreateUserAction::class);
    $app->get('/users/{id}', GetUserAction::class);
    $app->put('/users/{id}', UpdateUserAction::class);
    $app->delete('/users/{id}', DeleteUserAction::class);

    $app->get('/currencies', ListCurrenciesAction::class);
    $app->post('/currencies', CreateCurrencyAction::class);
    $app->get('/currencies/{id}', GetCurrencyAction::class);
    $app->put('/currencies/{id}', UpdateCurrencyAction::class);
    $app->delete('/currencies/{id}', DeleteCurrencyAction::class);

    $app->get('/accounts', ListAccountsAction::class);
    $app->post('/accounts', CreateAccountAction::class);
    $app->get('/accounts/{id}', GetAccountAction::class);
    $app->put('/accounts/{id}', UpdateAccountAction::class);
    $app->delete('/accounts/{id}', DeleteAccountAction::class);

    $app->get('/transactions', ListTransactionsAction::class);
    $app->post('/transactions', CreateTransactionAction::class);
    $app->get('/transactions/{id}', GetTransactionAction::class);
};
