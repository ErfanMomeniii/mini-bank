<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\Repository;

use ErfanMomeniii\MiniBank\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;

    /** @return User[] */
    public function findAll(): array;

    public function save(User $user): User;

    public function delete(int $id): void;
}
