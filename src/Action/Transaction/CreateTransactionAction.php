<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Action\Transaction;

use ErfanMomeniii\MiniBank\Domain\DTO\CreateTransactionRequest;
use ErfanMomeniii\MiniBank\Domain\Service\TransactionService;
use ErfanMomeniii\MiniBank\Responder\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class CreateTransactionAction
{
    public function __construct(
        private TransactionService $transactionService,
        private ValidatorInterface $validator,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = (array) json_decode((string) $request->getBody(), true);
        $dto = CreateTransactionRequest::fromArray($body);

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->responder->json($response, ['errors' => (string) $errors], 400);
        }

        $transaction = $this->transactionService->transfer(
            $dto->fromAccountId, $dto->toAccountId, $dto->amount, $dto->idempotencyKey,
        );

        return $this->responder->json($response, $transaction->toArray(), 201);
    }
}
