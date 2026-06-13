<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Infrastructure\Http\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

final readonly class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(private string $jwtKey)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $header = $request->getHeaderLine('Authorization');

        if (!str_starts_with($header, 'Bearer ')) {
            return $this->unauthorized('Missing or malformed Authorization header');
        }

        $token = substr($header, 7);

        try {
            $decoded = JWT::decode($token, new Key($this->jwtKey, 'HS256'));
        } catch (\Throwable) {
            return $this->unauthorized('Invalid or expired token');
        }

        $request = $request->withAttribute('userId', (int) $decoded->sub);

        return $handler->handle($request);
    }

    private function unauthorized(string $message): ResponseInterface
    {
        $response = new Response(401);
        $response->getBody()->write(json_encode(
            ['error' => $message],
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES,
        ));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
