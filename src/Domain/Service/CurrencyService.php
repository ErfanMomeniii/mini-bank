<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\Service;

use ErfanMomeniii\MiniBank\Domain\Entity\Currency;
use ErfanMomeniii\MiniBank\Domain\Exception\NotFoundException;
use ErfanMomeniii\MiniBank\Domain\Repository\CurrencyRepositoryInterface;

final readonly class CurrencyService
{
    public function __construct(private CurrencyRepositoryInterface $currencyRepository)
    {
    }

    public function create(string $name, string $code, string $symbol): Currency
    {
        $currency = new Currency(null, $name, $code, $symbol);
        return $this->currencyRepository->save($currency);
    }

    public function findById(int $id): Currency
    {
        return $this->currencyRepository->findById($id)
            ?? throw new NotFoundException("Currency with id {$id} not found.");
    }

    public function findAll(): array
    {
        return $this->currencyRepository->findAll();
    }

    public function update(int $id, string $name, string $code, string $symbol): Currency
    {
        $this->findById($id);
        $currency = new Currency($id, $name, $code, $symbol);
        return $this->currencyRepository->save($currency);
    }

    public function delete(int $id): void
    {
        $this->findById($id);
        $this->currencyRepository->delete($id);
    }
}
