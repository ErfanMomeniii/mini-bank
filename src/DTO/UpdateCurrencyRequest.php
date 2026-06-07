<?php declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdateCurrencyRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
        #[Assert\NotBlank]
        public string $code,
        #[Assert\NotBlank]
        public string $symbol,
    )
    {

    }


    public static function fromArray(array $array): self
    {
        return new self(
            $array['name'],
            $array['code'],
            $array['symbol'],
        );
    }
}