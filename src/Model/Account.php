<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Model;

/**
 * Represents a bank account owned by a user.
 *
 * An account holds a balance in a specific currency and can be active or inactive.
 * Transactions are performed between accounts.
 */
class Account
{
    /**
     * @param int|null $id Auto-generated account ID (null before persistence).
     * @param int $userId ID of the user who owns this account.
     * @param int $balance Current balance of the account.
     * @param Currency $currency The currency this account operates in.
     * @param AccountStatus $status Current status of the account.
     */
    public function __construct(
        private ?int          $id,
        private int           $userId,
        private int           $balance,
        private Currency      $currency,
        private AccountStatus $status,
    )
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getStatus(): AccountStatus
    {
        return $this->status;
    }

    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
    }

    public function setStatus(AccountStatus $status): void
    {
        $this->status = $status;
    }
}