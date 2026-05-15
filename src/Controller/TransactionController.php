<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Controller;

use ErfanMomeniii\MiniBank\Model\Transaction;
use ErfanMomeniii\MiniBank\Service\TransactionService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class TransactionController
{
    public function __construct(private TransactionService $transactionService)
    {
    }

    public function index(Request $request, Response $response): Response
    {
        $transactions = array_map(fn(Transaction $t) => $this->serialize($t), $this->transactionService->findAll());
        return $this->json($response, $transactions);
    }

    public function create(Request $request, Response $response): Response
    {
        $body = $this->body($request);
        $transaction = $this->transactionService->transfer(
            (int) ($body['from_account_id'] ?? 0),
            (int) ($body['to_account_id'] ?? 0),
            (int) ($body['amount'] ?? 0),
            $body['idempotency_key'] ?? '',
        );
        return $this->json($response, $this->serialize($transaction), 201);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $transaction = $this->transactionService->findById($args['id']);
        return $this->json($response, $this->serialize($transaction));
    }

    private function serialize(Transaction $transaction): array
    {
        return [
            'id'               => $transaction->getId(),
            'from_account_id'  => $transaction->getFromAccountId(),
            'to_account_id'    => $transaction->getToAccountId(),
            'amount'           => $transaction->getAmount(),
            'currency_id'      => $transaction->getCurrency()->getId(),
            'idempotency_key'  => $transaction->getIdempotencyKey(),
            'status'           => $transaction->getStatus()->value,
        ];
    }

    private function json(Response $response, mixed $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    private function body(Request $request): array
    {
        return (array) json_decode((string) $request->getBody(), true);
    }
}
