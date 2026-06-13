<?php

declare(strict_types=1);

use ErfanMomeniii\MiniBank\Action\Account\CreateAccountAction;
use ErfanMomeniii\MiniBank\Action\Account\DeleteAccountAction;
use ErfanMomeniii\MiniBank\Action\Account\GetAccountAction;
use ErfanMomeniii\MiniBank\Action\Account\ListAccountsAction;
use ErfanMomeniii\MiniBank\Action\Account\UpdateAccountAction;
use ErfanMomeniii\MiniBank\Action\Auth\LoginAction;
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
use ErfanMomeniii\MiniBank\Infrastructure\Http\Middleware\AuthMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app): void {
    $app->post('/auth/login', LoginAction::class);
    $app->post('/users', CreateUserAction::class);

    $auth = $app->getContainer()->get(AuthMiddleware::class);

    $app->group('', function (RouteCollectorProxy $group) {
        $group->get('/users', ListUsersAction::class);
        $group->get('/users/{id}', GetUserAction::class);
        $group->put('/users/{id}', UpdateUserAction::class);
        $group->delete('/users/{id}', DeleteUserAction::class);

        $group->get('/currencies', ListCurrenciesAction::class);
        $group->post('/currencies', CreateCurrencyAction::class);
        $group->get('/currencies/{id}', GetCurrencyAction::class);
        $group->put('/currencies/{id}', UpdateCurrencyAction::class);
        $group->delete('/currencies/{id}', DeleteCurrencyAction::class);

        $group->get('/accounts', ListAccountsAction::class);
        $group->post('/accounts', CreateAccountAction::class);
        $group->get('/accounts/{id}', GetAccountAction::class);
        $group->put('/accounts/{id}', UpdateAccountAction::class);
        $group->delete('/accounts/{id}', DeleteAccountAction::class);

        $group->get('/transactions', ListTransactionsAction::class);
        $group->post('/transactions', CreateTransactionAction::class);
        $group->get('/transactions/{id}', GetTransactionAction::class);
    })->add($auth);
};
