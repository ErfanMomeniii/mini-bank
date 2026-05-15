<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Service;

use ErfanMomeniii\MiniBank\Exception\InsufficientFundsException;
use ErfanMomeniii\MiniBank\Exception\NotFoundException;
use ErfanMomeniii\MiniBank\Exception\ValidationException;
use ErfanMomeniii\MiniBank\Model\AccountStatus;
use ErfanMomeniii\MiniBank\Model\Transaction;
use ErfanMomeniii\MiniBank\Model\TransactionStatus;
use ErfanMomeniii\MiniBank\Repository\AccountRepositoryInterface;
use ErfanMomeniii\MiniBank\Repository\TransactionRepositoryInterface;

class TransactionService
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
        // Idempotency check
        $existing = $this->transactionRepository->findByIdempotencyKey($idempotencyKey);
        if ($existing !== null) {
            return $existing;
        }

        $fromAccount = $this->accountRepository->findById($fromAccountId);
        if ($fromAccount === null) {
            throw new NotFoundException("Account with id {$fromAccountId} not found.");
        }

        $toAccount = $this->accountRepository->findById($toAccountId);
        if ($toAccount === null) {
            throw new NotFoundException("Account with id {$toAccountId} not found.");
        }

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
        $transaction = $this->transactionRepository->findById($id);
        if ($transaction === null) {
            throw new NotFoundException("Transaction with id {$id} not found.");
        }
        return $transaction;
    }

    public function findAll(): array
    {
        return $this->transactionRepository->findAll();
    }
}
