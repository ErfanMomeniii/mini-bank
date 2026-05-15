<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use ErfanMomeniii\MiniBank\Exception\InsufficientFundsException;
use ErfanMomeniii\MiniBank\Exception\NotFoundException;
use ErfanMomeniii\MiniBank\Exception\ValidationException;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

require __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../src/App/dependencies.php');
$container = $builder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

// Routes
(require __DIR__ . '/../src/App/routes.php')($app);

// Error handler
$errorMiddleware = $app->addErrorMiddleware(false, false, false);
$errorMiddleware->setDefaultErrorHandler(
    function (ServerRequestInterface $request, Throwable $exception) use ($app): ResponseInterface {
        $response = new Response();
        $response->getBody()->write(json_encode(match (true) {
            $exception instanceof NotFoundException          => ['error'  => $exception->getMessage()],
            $exception instanceof ValidationException        => ['errors' => $exception->getErrors()],
            $exception instanceof InsufficientFundsException => ['error'  => $exception->getMessage()],
            default                                          => ['error'  => 'Internal server error'],
        }));

        $status = match (true) {
            $exception instanceof NotFoundException          => 404,
            $exception instanceof ValidationException        => 422,
            $exception instanceof InsufficientFundsException => 422,
            default                                          => 500,
        };

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
);

$app->run();
