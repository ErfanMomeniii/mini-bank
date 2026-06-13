<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Action\Currency;

use ErfanMomeniii\MiniBank\Domain\Service\CurrencyService;
use ErfanMomeniii\MiniBank\Responder\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class DeleteCurrencyAction
{
    public function __construct(
        private CurrencyService $currencyService,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->currencyService->delete((int) $args['id']);

        return $this->responder->empty($response);
    }
}
