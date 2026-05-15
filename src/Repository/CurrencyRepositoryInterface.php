<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Repository;

use ErfanMomeniii\MiniBank\Model\Currency;

interface CurrencyRepositoryInterface
{
    public function findById(int $id): ?Currency;

    /** @return Currency[] */
    public function findAll(): array;

    public function save(Currency $currency): Currency;

    public function delete(int $id): void;
}
