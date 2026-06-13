<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\Repository;

use ErfanMomeniii\MiniBank\Domain\Entity\Currency;

interface CurrencyRepositoryInterface
{
    public function findById(int $id): ?Currency;

    /** @return Currency[] */
    public function findAll(): array;

    public function save(Currency $currency): Currency;

    public function delete(int $id): void;
}
