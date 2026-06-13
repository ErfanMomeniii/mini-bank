<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\Entity;

class Account
{
    public function __construct(
        private ?int          $id,
        private int           $userId,
        private int           $balance,
        private Currency      $currency,
        private AccountStatus $status,
    ) {
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'balance' => $this->balance,
            'currency_id' => $this->currency->getId(),
            'status' => $this->status->value,
        ];
    }
}
