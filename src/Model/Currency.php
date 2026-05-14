<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Model;

/**
 * Represents a currency used in transactions and accounts.
 *
 * Currencies are identified by a human-readable name and a symbol (e.g. "US Dollar", "$").
 * Ideally the symbol follows the ISO 4217 standard.
 */
class Currency
{
    /**
     * @param int|null $id Auto-generated currency ID (null before persistence).
     * @param string $name Full currency name (e.g. "US Dollar").
     * @param string $code ISO 4217 code (e.g. "$")
     * @param string $symbol Currency symbol (e.g. "USD").
     */
    public function __construct(
        private ?int   $id,
        private string $name,
        private string $code,
        private string $symbol,
    )
    {
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
}