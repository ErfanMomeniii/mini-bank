<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Action\Account;

use ErfanMomeniii\MiniBank\Domain\Service\AccountService;
use ErfanMomeniii\MiniBank\Responder\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class GetAccountAction
{
    public function __construct(
        private AccountService $accountService,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $account = $this->accountService->findById((int) $args['id']);

        return $this->responder->json($response, $account->toArray());
    }
}
