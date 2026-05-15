<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Repository\PostgreSQL;

use ErfanMomeniii\MiniBank\Model\Currency;
use ErfanMomeniii\MiniBank\Repository\CurrencyRepositoryInterface;
use PDO;
use ReflectionProperty;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findById(int $id): ?Currency
    {
        $stmt = $this->pdo->prepare('SELECT id, name, code, symbol FROM currencies WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row !== false ? $this->hydrate($row) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT id, name, code, symbol FROM currencies');

        return array_map(fn(array $row) => $this->hydrate($row), $stmt->fetchAll());
    }

    public function save(Currency $currency): Currency
    {
        if ($currency->getId() === null) {
            $stmt = $this->pdo->prepare(
                'INSERT INTO currencies (name, code, symbol) VALUES (:name, :code, :symbol) RETURNING id'
            );
            $stmt->execute([
                ':name'   => $currency->getName(),
                ':code'   => $currency->getCode(),
                ':symbol' => $currency->getSymbol(),
            ]);
            $id = (int) $stmt->fetchColumn();
            $this->setId($currency, $id);
        } else {
            $stmt = $this->pdo->prepare(
                'UPDATE currencies SET name = :name, code = :code, symbol = :symbol WHERE id = :id'
            );
            $stmt->execute([
                ':name'   => $currency->getName(),
                ':code'   => $currency->getCode(),
                ':symbol' => $currency->getSymbol(),
                ':id'     => $currency->getId(),
            ]);
        }

        return $currency;
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM currencies WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    private function hydrate(array $row): Currency
    {
        return new Currency(
            (int) $row['id'],
            $row['name'],
            $row['code'],
            $row['symbol'],
        );
    }

    private function setId(Currency $currency, int $id): void
    {
        $prop = new ReflectionProperty(Currency::class, 'id');
        $prop->setValue($currency, $id);
    }
}
