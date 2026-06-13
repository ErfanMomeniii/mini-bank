<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\Repository;

use ErfanMomeniii\MiniBank\Domain\Entity\Transaction;

interface TransactionRepositoryInterface
{
    public function findById(string $id): ?Transaction;

    public function findByIdempotencyKey(string $key): ?Transaction;

    /** @return Transaction[] */
    public function findAll(): array;

    public function save(Transaction $transaction): Transaction;

    public function delete(string $id): void;
}
