<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\Entity;

class User
{
    public function __construct(
        private ?int       $id,
        private string     $phoneNumber,
        private UserStatus $status,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getStatus(): UserStatus
    {
        return $this->status;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function setStatus(UserStatus $status): void
    {
        $this->status = $status;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'phone_number' => $this->phoneNumber,
            'status' => $this->status->value,
        ];
    }
}
