<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Model;

/**
 * Represents a money transfer between two accounts.
 *
 * A transaction moves a positive amount of a given currency from one account to another.
 * The idempotency key ensures that retrying the same request does not result in duplicate transfers.
 * Either fromAccountId or toAccountId may be null to represent deposits or withdrawals respectively.
 */
class Transaction
{
    /**
     * @param string $id Auto-generated transaction ID (null before persistence).
     * @param int|null $fromAccountId Source account ID, or null for external deposits.
     * @param int|null $toAccountId Destination account ID, or null for external withdrawals.
     * @param int $amount Transfer amount in the smallest currency unit (e.g. cents). Must be positive.
     * @param Currency $currency The currency of the transfer.
     * @param string $idempotencyKey Unique key to prevent duplicate transactions on retry.
     * @param TransactionStatus $status
     */
    public function __construct(
        private string            $id,
        private ?int              $fromAccountId,
        private ?int              $toAccountId,
        private int               $amount,
        private Currency          $currency,
        private string            $idempotencyKey,
        private TransactionStatus $status,
    )
    {
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
}