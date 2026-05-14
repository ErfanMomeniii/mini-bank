<?php
declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Model;

/**
 * Represents a bank customer.
 *
 * Users are identified by a phone number and can own one or more accounts.
 * A blocked user should not be able to initiate or receive transactions.
 */
class User
{
    /**
     * @param int|null $id Auto-generated user ID (null before persistence).
     * @param string $phoneNumber User's phone number used as the primary identifier.
     * @param UserStatus $status Current status of the user account.
     */
    public function __construct(
        private ?int       $id,
        private string     $phoneNumber,
        private UserStatus $status,
    )
    {
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
}