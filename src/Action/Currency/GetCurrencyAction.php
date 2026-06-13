<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Action\Currency;

use ErfanMomeniii\MiniBank\Domain\Service\CurrencyService;
use ErfanMomeniii\MiniBank\Responder\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class GetCurrencyAction
{
    public function __construct(
        private CurrencyService $currencyService,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $currency = $this->currencyService->findById((int) $args['id']);

        return $this->responder->json($response, $currency->toArray());
    }
}
