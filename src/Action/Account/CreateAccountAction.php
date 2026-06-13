<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Action\Account;

use ErfanMomeniii\MiniBank\Domain\DTO\CreateAccountRequest;
use ErfanMomeniii\MiniBank\Domain\Service\AccountService;
use ErfanMomeniii\MiniBank\Responder\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class CreateAccountAction
{
    public function __construct(
        private AccountService $accountService,
        private ValidatorInterface $validator,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = (array) json_decode((string) $request->getBody(), true);
        $dto = CreateAccountRequest::fromArray($body);

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->responder->json($response, ['errors' => (string) $errors], 400);
        }

        $account = $this->accountService->create($dto->userId, $dto->currencyId, $dto->balance);

        return $this->responder->json($response, $account->toArray(), 201);
    }
}
