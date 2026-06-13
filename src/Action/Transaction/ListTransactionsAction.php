<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Action\Transaction;

use ErfanMomeniii\MiniBank\Domain\Service\TransactionService;
use ErfanMomeniii\MiniBank\Responder\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class ListTransactionsAction
{
    public function __construct(
        private TransactionService $transactionService,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $transactions = array_map(fn($t) => $t->toArray(), $this->transactionService->findAll());

        return $this->responder->json($response, $transactions);
    }
}
