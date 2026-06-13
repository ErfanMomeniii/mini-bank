<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateTransactionRequest
{
    public function __construct(
        #[Assert\Positive]
        public int $fromAccountId,
        #[Assert\Positive]
        public int $toAccountId,
        #[Assert\Positive]
        public int $amount,
        #[Assert\NotBlank]
        public string $idempotencyKey,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['from_account_id'] ?? 0),
            (int) ($data['to_account_id'] ?? 0),
            (int) ($data['amount'] ?? 0),
            $data['idempotency_key'] ?? '',
        );
    }
}
