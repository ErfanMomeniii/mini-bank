<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Repository\PostgreSQL;

use ErfanMomeniii\MiniBank\Model\Account;
use ErfanMomeniii\MiniBank\Model\AccountStatus;
use ErfanMomeniii\MiniBank\Repository\AccountRepositoryInterface;
use ErfanMomeniii\MiniBank\Repository\CurrencyRepositoryInterface;
use PDO;
use ReflectionProperty;

class AccountRepository implements AccountRepositoryInterface
{
    public function __construct(
        private PDO                         $pdo,
        private CurrencyRepositoryInterface $currencyRepository,
    ) {
    }

    public function findById(int $id): ?Account
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, user_id, balance, currency_id, status FROM accounts WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row !== false ? $this->hydrate($row) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT id, user_id, balance, currency_id, status FROM accounts');

        return array_map(fn(array $row) => $this->hydrate($row), $stmt->fetchAll());
    }

    public function save(Account $account): Account
    {
        if ($account->getId() === null) {
            $stmt = $this->pdo->prepare(
                'INSERT INTO accounts (user_id, balance, currency_id, status)
                 VALUES (:user_id, :balance, :currency_id, :status)
                 RETURNING id'
            );
            $stmt->execute([
                ':user_id'     => $account->getUserId(),
                ':balance'     => $account->getBalance(),
                ':currency_id' => $account->getCurrency()->getId(),
                ':status'      => $account->getStatus()->value,
            ]);
            $id = (int) $stmt->fetchColumn();
            $this->setId($account, $id);
        } else {
            $stmt = $this->pdo->prepare(
                'UPDATE accounts
                 SET user_id = :user_id, balance = :balance, currency_id = :currency_id, status = :status
                 WHERE id = :id'
            );
            $stmt->execute([
                ':user_id'     => $account->getUserId(),
                ':balance'     => $account->getBalance(),
                ':currency_id' => $account->getCurrency()->getId(),
                ':status'      => $account->getStatus()->value,
                ':id'          => $account->getId(),
            ]);
        }

        return $account;
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM accounts WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    private function hydrate(array $row): Account
    {
        $currency = $this->currencyRepository->findById((int) $row['currency_id']);

        return new Account(
            (int) $row['id'],
            (int) $row['user_id'],
            (int) $row['balance'],
            $currency,
            AccountStatus::from($row['status']),
        );
    }

    private function setId(Account $account, int $id): void
    {
        $prop = new ReflectionProperty(Account::class, 'id');
        $prop->setAccessible(true);
        $prop->setValue($account, $id);
    }
}
