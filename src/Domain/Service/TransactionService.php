<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\Service;

use ErfanMomeniii\MiniBank\Domain\Entity\AccountStatus;
use ErfanMomeniii\MiniBank\Domain\Entity\Transaction;
use ErfanMomeniii\MiniBank\Domain\Entity\TransactionStatus;
use ErfanMomeniii\MiniBank\Domain\Exception\InsufficientFundsException;
use ErfanMomeniii\MiniBank\Domain\Exception\NotFoundException;
use ErfanMomeniii\MiniBank\Domain\Exception\ValidationException;
use ErfanMomeniii\MiniBank\Domain\Repository\AccountRepositoryInterface;
use ErfanMomeniii\MiniBank\Domain\Repository\TransactionRepositoryInterface;

final readonly class TransactionService
{
    public function __construct(
        private TransactionRepositoryInterface $transactionRepository,
        private AccountRepositoryInterface     $accountRepository,
    ) {
    }

    public function transfer(
        int    $fromAccountId,
        int    $toAccountId,
        int    $amount,
        string $idempotencyKey,
    ): Transaction {
        $existing = $this->transactionRepository->findByIdempotencyKey($idempotencyKey);
        if ($existing !== null) {
            return $existing;
        }

        $fromAccount = $this->accountRepository->findById($fromAccountId)
            ?? throw new NotFoundException("Account with id {$fromAccountId} not found.");

        $toAccount = $this->accountRepository->findById($toAccountId)
            ?? throw new NotFoundException("Account with id {$toAccountId} not found.");

        if ($fromAccount->getStatus() !== AccountStatus::Active) {
            throw new ValidationException(["Source account {$fromAccountId} is not active."]);
        }

        if ($toAccount->getStatus() !== AccountStatus::Active) {
            throw new ValidationException(["Destination account {$toAccountId} is not active."]);
        }

        if ($fromAccount->getCurrency()->getId() !== $toAccount->getCurrency()->getId()) {
            throw new ValidationException(['Accounts must use the same currency.']);
        }

        if ($fromAccount->getBalance() < $amount) {
            throw new InsufficientFundsException(
                "Insufficient funds: balance {$fromAccount->getBalance()}, requested {$amount}."
            );
        }

        $transaction = new Transaction(
            '',
            $fromAccountId,
            $toAccountId,
            $amount,
            $fromAccount->getCurrency(),
            $idempotencyKey,
            TransactionStatus::Pending,
        );
        $transaction = $this->transactionRepository->save($transaction);

        try {
            $fromAccount->setBalance($fromAccount->getBalance() - $amount);
            $toAccount->setBalance($toAccount->getBalance() + $amount);
            $this->accountRepository->save($fromAccount);
            $this->accountRepository->save($toAccount);

            $transaction->setStatus(TransactionStatus::Success);
            $this->transactionRepository->save($transaction);
        } catch (\Throwable $e) {
            $transaction->setStatus(TransactionStatus::Failed);
            $this->transactionRepository->save($transaction);
            throw $e;
        }

        return $transaction;
    }

    public function findById(string $id): Transaction
    {
        return $this->transactionRepository->findById($id)
            ?? throw new NotFoundException("Transaction with id {$id} not found.");
    }

    public function findAll(): array
    {
        return $this->transactionRepository->findAll();
    }
}
