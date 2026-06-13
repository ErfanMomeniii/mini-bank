<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Action\User;

use ErfanMomeniii\MiniBank\Domain\Service\UserService;
use ErfanMomeniii\MiniBank\Responder\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class DeleteUserAction
{
    public function __construct(
        private UserService $userService,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->userService->delete((int) $args['id']);

        return $this->responder->empty($response);
    }
}
