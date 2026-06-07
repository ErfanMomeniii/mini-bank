<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Controller;

use ErfanMomeniii\MiniBank\DTO\CreateCurrencyRequest;
use ErfanMomeniii\MiniBank\DTO\UpdateCurrencyRequest;
use ErfanMomeniii\MiniBank\Model\Currency;
use ErfanMomeniii\MiniBank\Service\CurrencyService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class CurrencyController
{
    public function __construct(private CurrencyService $currencyService, private ValidatorInterface $validator)
    {
    }

    public function index(Request $request, Response $response): Response
    {
        $currencies = array_map(fn(Currency $c) => $this->serialize($c), $this->currencyService->findAll());
        return $this->json($response, $currencies);
    }

    public function create(Request $request, Response $response): Response
    {
        $req = CreateCurrencyRequest::fromArray($this->body($request));

        $error = $this->validator->validate($req);
        if (count($error) > 0) {
            return $this->json($response, $error, 400);
        }

        $currency = $this->currencyService->create(
            $req->name, $req->code, $req->symbol,
        );

        return $this->json($response, $this->serialize($currency), 201);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $currency = $this->currencyService->findById((int)$args['id']);

        return $this->json($response, $this->serialize($currency));
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $req = UpdateCurrencyRequest::fromArray($this->body($request));

        $error = $this->validator->validate($req);
        if (count($error) > 0) {
            return $this->json($response, $error, 400);
        }

        $currency = $this->currencyService->update(
            (int)$args['id'], $req->name, $req->code, $req->symbol,
        );

        return $this->json($response, $this->serialize($currency));
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        $this->currencyService->delete((int)$args['id']);

        return $response->withStatus(204);
    }

    private function serialize(Currency $currency): array
    {
        return [
            'id' => $currency->getId(),
            'name' => $currency->getName(),
            'code' => $currency->getCode(),
            'symbol' => $currency->getSymbol(),
        ];
    }

    private function json(Response $response, mixed $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    private function body(Request $request): array
    {
        return (array)json_decode((string)$request->getBody(), true);
    }
}
