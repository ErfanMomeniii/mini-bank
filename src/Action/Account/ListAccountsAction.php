<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Action\Account;

use ErfanMomeniii\MiniBank\Domain\Service\AccountService;
use ErfanMomeniii\MiniBank\Responder\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class ListAccountsAction
{
    public function __construct(
        private AccountService $accountService,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $accounts = array_map(fn($a) => $a->toArray(), $this->accountService->findAll());

        return $this->responder->json($response, $accounts);
    }
}
