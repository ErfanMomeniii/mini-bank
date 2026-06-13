<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\Entity;

class Transaction
{
    public function __construct(
        private string            $id,
        private ?int              $fromAccountId,
        private ?int              $toAccountId,
        private int               $amount,
        private Currency          $currency,
        private string            $idempotencyKey,
        private TransactionStatus $status,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFromAccountId(): ?int
    {
        return $this->fromAccountId;
    }

    public function getToAccountId(): ?int
    {
        return $this->toAccountId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getIdempotencyKey(): string
    {
        return $this->idempotencyKey;
    }

    public function getStatus(): TransactionStatus
    {
        return $this->status;
    }

    public function setStatus(TransactionStatus $status): void
    {
        $this->status = $status;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'from_account_id' => $this->fromAccountId,
            'to_account_id' => $this->toAccountId,
            'amount' => $this->amount,
            'currency_id' => $this->currency->getId(),
            'idempotency_key' => $this->idempotencyKey,
            'status' => $this->status->value,
        ];
    }
}
