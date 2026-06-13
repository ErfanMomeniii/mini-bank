<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Domain\Entity;

class Currency
{
    public function __construct(
        private ?int   $id,
        private string $name,
        private string $code,
        private string $symbol,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'symbol' => $this->symbol,
        ];
    }
}
