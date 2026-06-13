<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Infrastructure\Persistence;

use ErfanMomeniii\MiniBank\Domain\Entity\Transaction;
use ErfanMomeniii\MiniBank\Domain\Entity\TransactionStatus;
use ErfanMomeniii\MiniBank\Domain\Repository\CurrencyRepositoryInterface;
use ErfanMomeniii\MiniBank\Domain\Repository\TransactionRepositoryInterface;
use PDO;
use ReflectionProperty;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function __construct(
        private PDO                                  $pdo,
        private readonly CurrencyRepositoryInterface $currencyRepository,
    ) {
    }

    public function findById(string $id): ?Transaction
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, from_account_id, to_account_id, amount, currency_id, idempotency_key, status
             FROM transactions WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row !== false ? $this->hydrate($row) : null;
    }

    public function findByIdempotencyKey(string $key): ?Transaction
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, from_account_id, to_account_id, amount, currency_id, idempotency_key, status
             FROM transactions WHERE idempotency_key = :key'
        );
        $stmt->execute([':key' => $key]);
        $row = $stmt->fetch();

        return $row !== false ? $this->hydrate($row) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT id, from_account_id, to_account_id, amount, currency_id, idempotency_key, status
             FROM transactions'
        );

        return array_map(fn(array $row) => $this->hydrate($row), $stmt->fetchAll());
    }

    public function save(Transaction $transaction): Transaction
    {
        if ($transaction->getId() === '') {
            $stmt = $this->pdo->prepare(
                'INSERT INTO transactions (from_account_id, to_account_id, amount, currency_id, idempotency_key, status)
                 VALUES (:from_account_id, :to_account_id, :amount, :currency_id, :idempotency_key, :status)
                 RETURNING id'
            );
            $stmt->execute([
                ':from_account_id' => $transaction->getFromAccountId(),
                ':to_account_id'   => $transaction->getToAccountId(),
                ':amount'          => $transaction->getAmount(),
                ':currency_id'     => $transaction->getCurrency()->getId(),
                ':idempotency_key' => $transaction->getIdempotencyKey(),
                ':status'          => $transaction->getStatus()->value,
            ]);
            $id = $stmt->fetchColumn();
            $this->setId($transaction, (string) $id);
        } else {
            $stmt = $this->pdo->prepare(
                'UPDATE transactions
                 SET from_account_id = :from_account_id,
                     to_account_id   = :to_account_id,
                     amount          = :amount,
                     currency_id     = :currency_id,
                     idempotency_key = :idempotency_key,
                     status          = :status
                 WHERE id = :id'
            );
            $stmt->execute([
                ':from_account_id' => $transaction->getFromAccountId(),
                ':to_account_id'   => $transaction->getToAccountId(),
                ':amount'          => $transaction->getAmount(),
                ':currency_id'     => $transaction->getCurrency()->getId(),
                ':idempotency_key' => $transaction->getIdempotencyKey(),
                ':status'          => $transaction->getStatus()->value,
                ':id'              => $transaction->getId(),
            ]);
        }

        return $transaction;
    }

    public function delete(string $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM transactions WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    private function hydrate(array $row): Transaction
    {
        $currency = $this->currencyRepository->findById((int) $row['currency_id']);

        return new Transaction(
            $row['id'],
            $row['from_account_id'] !== null ? (int) $row['from_account_id'] : null,
            $row['to_account_id'] !== null ? (int) $row['to_account_id'] : null,
            (int) $row['amount'],
            $currency,
            $row['idempotency_key'],
            TransactionStatus::from($row['status']),
        );
    }

    private function setId(Transaction $transaction, string $id): void
    {
        $prop = new ReflectionProperty(Transaction::class, 'id');
        $prop->setValue($transaction, $id);
    }
}
