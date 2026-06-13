<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\Service;

use ErfanMomeniii\MiniBank\Domain\Entity\Account;
use ErfanMomeniii\MiniBank\Domain\Entity\AccountStatus;
use ErfanMomeniii\MiniBank\Domain\Exception\NotFoundException;
use ErfanMomeniii\MiniBank\Domain\Repository\AccountRepositoryInterface;
use ErfanMomeniii\MiniBank\Domain\Repository\CurrencyRepositoryInterface;
use ErfanMomeniii\MiniBank\Domain\Repository\UserRepositoryInterface;

final readonly class AccountService
{
    public function __construct(
        private AccountRepositoryInterface  $accountRepository,
        private CurrencyRepositoryInterface $currencyRepository,
        private UserRepositoryInterface     $userRepository,
    ) {
    }

    public function create(int $userId, int $currencyId, int $initialBalance = 0): Account
    {
        $this->userRepository->findById($userId)
            ?? throw new NotFoundException("User with id {$userId} not found.");

        $currency = $this->currencyRepository->findById($currencyId)
            ?? throw new NotFoundException("Currency with id {$currencyId} not found.");

        $account = new Account(null, $userId, $initialBalance, $currency, AccountStatus::Active);
        return $this->accountRepository->save($account);
    }

    public function findById(int $id): Account
    {
        return $this->accountRepository->findById($id)
            ?? throw new NotFoundException("Account with id {$id} not found.");
    }

    public function findAll(): array
    {
        return $this->accountRepository->findAll();
    }

    public function updateStatus(int $id, string $status): Account
    {
        $account = $this->findById($id);
        $account->setStatus(AccountStatus::from($status));
        return $this->accountRepository->save($account);
    }

    public function delete(int $id): void
    {
        $this->findById($id);
        $this->accountRepository->delete($id);
    }
}
