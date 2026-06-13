<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Action\Auth;

use ErfanMomeniii\MiniBank\Domain\Exception\NotFoundException;
use ErfanMomeniii\MiniBank\Domain\Repository\UserRepositoryInterface;
use ErfanMomeniii\MiniBank\Responder\JsonResponder;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class LoginAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private JsonResponder $responder,
        private string $jwtKey,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = (array) json_decode((string) $request->getBody(), true);
        $phoneNumber = $body['phone_number'] ?? '';

        $users = $this->userRepository->findAll();
        $user = null;
        foreach ($users as $u) {
            if ($u->getPhoneNumber() === $phoneNumber) {
                $user = $u;
                break;
            }
        }

        if ($user === null) {
            throw new NotFoundException('User not found.');
        }

        $now = time();
        $payload = [
            'sub' => $user->getId(),
            'iat' => $now,
            'exp' => $now + 3600,
        ];

        $token = JWT::encode($payload, $this->jwtKey, 'HS256');

        return $this->responder->json($response, [
            'token' => $token,
            'expires_in' => 3600,
        ]);
    }
}
