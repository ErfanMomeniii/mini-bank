<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Action\Account;

use ErfanMomeniii\MiniBank\Domain\DTO\UpdateAccountRequest;
use ErfanMomeniii\MiniBank\Domain\Service\AccountService;
use ErfanMomeniii\MiniBank\Responder\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class UpdateAccountAction
{
    public function __construct(
        private AccountService $accountService,
        private ValidatorInterface $validator,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $body = (array) json_decode((string) $request->getBody(), true);
        $dto = UpdateAccountRequest::fromArray($body);

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->responder->json($response, ['errors' => (string) $errors], 400);
        }

        $account = $this->accountService->updateStatus((int) $args['id'], $dto->status);

        return $this->responder->json($response, $account->toArray());
    }
}
