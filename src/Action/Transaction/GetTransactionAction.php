<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Action\Transaction;

use ErfanMomeniii\MiniBank\Domain\Service\TransactionService;
use ErfanMomeniii\MiniBank\Responder\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class GetTransactionAction
{
    public function __construct(
        private TransactionService $transactionService,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $transaction = $this->transactionService->findById($args['id']);

        return $this->responder->json($response, $transaction->toArray());
    }
}
