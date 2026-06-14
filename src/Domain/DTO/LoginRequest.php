<?php declare(strict_types=1);

namespace App\Domain\DTO;

use ErfanMomeniii\MiniBank\Domain\DTO\CreateAccountRequest;
use Symfony\Component\Validator\Constraints as Assert;

readonly class LoginRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $phoneNumber,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['phone_number'] ?? '',
        );
    }
}