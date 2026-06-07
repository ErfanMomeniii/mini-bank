<?php declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdateAccountRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(['Active', 'Inactive'])]
        public string $status
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['status'],
        );
    }
}