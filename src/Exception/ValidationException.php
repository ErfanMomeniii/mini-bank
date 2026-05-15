<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Exception;

class ValidationException extends \RuntimeException
{
    /** @param string[] $errors */
    public function __construct(private array $errors, string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message ?: implode(', ', $errors), $code, $previous);
    }

    /** @return string[] */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
