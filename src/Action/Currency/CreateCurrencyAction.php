<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Action\Currency;

use ErfanMomeniii\MiniBank\Domain\DTO\CreateCurrencyRequest;
use ErfanMomeniii\MiniBank\Domain\Service\CurrencyService;
use ErfanMomeniii\MiniBank\Responder\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class CreateCurrencyAction
{
    public function __construct(
        private CurrencyService $currencyService,
        private ValidatorInterface $validator,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = (array) json_decode((string) $request->getBody(), true);
        $dto = CreateCurrencyRequest::fromArray($body);

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->responder->json($response, ['errors' => (string) $errors], 400);
        }

        $currency = $this->currencyService->create($dto->name, $dto->code, $dto->symbol);

        return $this->responder->json($response, $currency->toArray(), 201);
    }
}
