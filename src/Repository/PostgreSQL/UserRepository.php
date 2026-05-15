<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Repository\PostgreSQL;

use ErfanMomeniii\MiniBank\Model\User;
use ErfanMomeniii\MiniBank\Model\UserStatus;
use ErfanMomeniii\MiniBank\Repository\UserRepositoryInterface;
use PDO;
use ReflectionProperty;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare('SELECT id, phone_number, status FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row !== false ? $this->hydrate($row) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT id, phone_number, status FROM users');

        return array_map(fn(array $row) => $this->hydrate($row), $stmt->fetchAll());
    }

    public function save(User $user): User
    {
        if ($user->getId() === null) {
            $stmt = $this->pdo->prepare(
                'INSERT INTO users (phone_number, status) VALUES (:phone_number, :status) RETURNING id'
            );
            $stmt->execute([
                ':phone_number' => $user->getPhoneNumber(),
                ':status'       => $user->getStatus()->value,
            ]);
            $id = (int) $stmt->fetchColumn();
            $this->setId($user, $id);
        } else {
            $stmt = $this->pdo->prepare(
                'UPDATE users SET phone_number = :phone_number, status = :status WHERE id = :id'
            );
            $stmt->execute([
                ':phone_number' => $user->getPhoneNumber(),
                ':status'       => $user->getStatus()->value,
                ':id'           => $user->getId(),
            ]);
        }

        return $user;
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    private function hydrate(array $row): User
    {
        return new User(
            (int) $row['id'],
            $row['phone_number'],
            UserStatus::from($row['status']),
        );
    }

    private function setId(User $user, int $id): void
    {
        $prop = new ReflectionProperty(User::class, 'id');
        $prop->setAccessible(true);
        $prop->setValue($user, $id);
    }
}
