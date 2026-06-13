<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateCurrencyRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
        #[Assert\NotBlank]
        public string $code,
        #[Assert\NotBlank]
        public string $symbol,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'] ?? '',
            $data['code'] ?? '',
            $data['symbol'] ?? '',
        );
    }
}
