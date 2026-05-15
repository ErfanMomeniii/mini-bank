<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Repository;

use ErfanMomeniii\MiniBank\Model\Account;

interface AccountRepositoryInterface
{
    public function findById(int $id): ?Account;

    /** @return Account[] */
    public function findAll(): array;

    public function save(Account $account): Account;

    public function delete(int $id): void;
}
