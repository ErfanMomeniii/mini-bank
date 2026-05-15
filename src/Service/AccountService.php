<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Service;

use ErfanMomeniii\MiniBank\Exception\NotFoundException;
use ErfanMomeniii\MiniBank\Model\Account;
use ErfanMomeniii\MiniBank\Model\AccountStatus;
use ErfanMomeniii\MiniBank\Repository\AccountRepositoryInterface;
use ErfanMomeniii\MiniBank\Repository\CurrencyRepositoryInterface;
use ErfanMomeniii\MiniBank\Repository\UserRepositoryInterface;

class AccountService
{
    public function __construct(
        private AccountRepositoryInterface  $accountRepository,
        private CurrencyRepositoryInterface $currencyRepository,
        private UserRepositoryInterface     $userRepository,
    ) {
    }

    public function create(int $userId, int $currencyId, int $initialBalance = 0): Account
    {
        $user = $this->userRepository->findById($userId);
        if ($user === null) {
            throw new NotFoundException("User with id {$userId} not found.");
        }

        $currency = $this->currencyRepository->findById($currencyId);
        if ($currency === null) {
            throw new NotFoundException("Currency with id {$currencyId} not found.");
        }

        $account = new Account(null, $userId, $initialBalance, $currency, AccountStatus::Active);
        return $this->accountRepository->save($account);
    }

    public function findById(int $id): Account
    {
        $account = $this->accountRepository->findById($id);
        if ($account === null) {
            throw new NotFoundException("Account with id {$id} not found.");
        }
        return $account;
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
