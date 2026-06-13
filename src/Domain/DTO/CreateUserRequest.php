<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateUserRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $phoneNumber,
        #[Assert\NotBlank]
        #[Assert\Choice(['Active', 'Blocked'])]
        public string $status,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['phone_number'] ?? '',
            $data['status'] ?? 'Active',
        );
    }
}
