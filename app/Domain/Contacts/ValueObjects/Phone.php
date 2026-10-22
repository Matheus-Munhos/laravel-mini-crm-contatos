<?php

namespace App\Domain\Contacts\ValueObjects;

use InvalidArgumentException;

class Phone
{
    private string $value;

    public function __construct(string $value)
    {
        $digits = preg_replace('/\D/', '', $value);

        if (strlen($digits) < 10 || strlen($digits) > 11) {
            throw new InvalidArgumentException("Telefone inválido.");
        }

        $this->value = $digits;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isSaoPaulo(): bool
    {
        $ddd = (int) substr($this->value, 0, 2);
        return $ddd >= 11 && $ddd <= 19;
    }
}