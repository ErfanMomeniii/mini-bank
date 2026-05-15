<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Controller;

use ErfanMomeniii\MiniBank\Model\Account;
use ErfanMomeniii\MiniBank\Service\AccountService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class AccountController
{
    public function __construct(private AccountService $accountService)
    {
    }

    public function index(Request $request, Response $response): Response
    {
        $accounts = array_map(fn(Account $a) => $this->serialize($a), $this->accountService->findAll());
        return $this->json($response, $accounts);
    }

    public function create(Request $request, Response $response): Response
    {
        $body = $this->body($request);
        $account = $this->accountService->create(
            (int) ($body['user_id'] ?? 0),
            (int) ($body['currency_id'] ?? 0),
            (int) ($body['balance'] ?? 0),
        );
        return $this->json($response, $this->serialize($account), 201);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $account = $this->accountService->findById((int) $args['id']);
        return $this->json($response, $this->serialize($account));
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $body = $this->body($request);
        $account = $this->accountService->updateStatus(
            (int) $args['id'],
            $body['status'] ?? 'Active',
        );
        return $this->json($response, $this->serialize($account));
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        $this->accountService->delete((int) $args['id']);
        return $response->withStatus(204);
    }

    private function serialize(Account $account): array
    {
        return [
            'id'          => $account->getId(),
            'user_id'     => $account->getUserId(),
            'balance'     => $account->getBalance(),
            'currency_id' => $account->getCurrency()->getId(),
            'status'      => $account->getStatus()->value,
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
