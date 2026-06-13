<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\Service;

use ErfanMomeniii\MiniBank\Domain\Entity\User;
use ErfanMomeniii\MiniBank\Domain\Entity\UserStatus;
use ErfanMomeniii\MiniBank\Domain\Exception\NotFoundException;
use ErfanMomeniii\MiniBank\Domain\Repository\UserRepositoryInterface;

final readonly class UserService
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function create(string $phoneNumber, string $status = 'Active'): User
    {
        $user = new User(null, $phoneNumber, UserStatus::from($status));
        return $this->userRepository->save($user);
    }

    public function findById(int $id): User
    {
        return $this->userRepository->findById($id)
            ?? throw new NotFoundException("User with id {$id} not found.");
    }

    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }

    public function update(int $id, string $phoneNumber, string $status): User
    {
        $user = $this->findById($id);
        $user->setPhoneNumber($phoneNumber);
        $user->setStatus(UserStatus::from($status));
        return $this->userRepository->save($user);
    }

    public function delete(int $id): void
    {
        $this->findById($id);
        $this->userRepository->delete($id);
    }
}
