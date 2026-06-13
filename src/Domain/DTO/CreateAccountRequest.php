<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateAccountRequest
{
    public function __construct(
        #[Assert\Positive]
        public int $userId,
        #[Assert\Positive]
        public int $currencyId,
        #[Assert\PositiveOrZero]
        public int $balance,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['user_id'] ?? 0),
            (int) ($data['currency_id'] ?? 0),
            (int) ($data['balance'] ?? 0),
        );
    }
}
