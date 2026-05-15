<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Service;

use ErfanMomeniii\MiniBank\Exception\NotFoundException;
use ErfanMomeniii\MiniBank\Model\User;
use ErfanMomeniii\MiniBank\Model\UserStatus;
use ErfanMomeniii\MiniBank\Repository\UserRepositoryInterface;

class UserService
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
        $user = $this->userRepository->findById($id);
        if ($user === null) {
            throw new NotFoundException("User with id {$id} not found.");
        }
        return $user;
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
