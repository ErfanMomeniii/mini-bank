<?php declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\DTO;

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
    )
    {
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['user_id'],
            $array['currency_id'],
            $array['balance'],
        );
    }
}