<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Infrastructure\Http;

use ErfanMomeniii\MiniBank\Domain\Exception\InsufficientFundsException;
use ErfanMomeniii\MiniBank\Domain\Exception\NotFoundException;
use ErfanMomeniii\MiniBank\Domain\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Psr7\Response;
use Throwable;

final readonly class JsonErrorHandler implements ErrorHandlerInterface
{
    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails,
    ): ResponseInterface {
        [$status, $body] = match (true) {
            $exception instanceof NotFoundException          => [404, ['error' => $exception->getMessage()]],
            $exception instanceof ValidationException        => [422, ['errors' => $exception->getErrors()]],
            $exception instanceof InsufficientFundsException => [422, ['error' => $exception->getMessage()]],
            $exception instanceof HttpNotFoundException      => [404, ['error' => 'Resource not found']],
            $exception instanceof HttpMethodNotAllowedException => [405, ['error' => 'Method not allowed']],
            default => [500, ['error' => $displayErrorDetails ? $exception->getMessage() : 'Internal server error']],
        };

        $response = new Response($status);
        $response->getBody()->write(json_encode($body, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
