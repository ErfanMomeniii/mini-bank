<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Action\User;

use ErfanMomeniii\MiniBank\Domain\DTO\UpdateUserRequest;
use ErfanMomeniii\MiniBank\Domain\Service\UserService;
use ErfanMomeniii\MiniBank\Responder\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class UpdateUserAction
{
    public function __construct(
        private UserService $userService,
        private ValidatorInterface $validator,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $body = (array) json_decode((string) $request->getBody(), true);
        $dto = UpdateUserRequest::fromArray($body);

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->responder->json($response, ['errors' => (string) $errors], 400);
        }

        $user = $this->userService->update((int) $args['id'], $dto->phoneNumber, $dto->status);

        return $this->responder->json($response, $user->toArray());
    }
}
