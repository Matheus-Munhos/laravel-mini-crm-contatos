<?php

namespace App\Domain\Contacts\ValueObjects;

use InvalidArgumentException;

class Email
{
    private string $value;

    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("E-mail inválido.");
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isBr(): bool
    {
        return str_ends_with($this->value, '.br');
    }

    public function isCorporate(): bool
    {
        $domain = substr(strrchr($this->value, "@"), 1);
        $commonProviders = ['gmail.com', 'hotmail.com', 'yahoo.com', 'outlook.com', 'icloud.com'];

        return !in_array(strtolower($domain), $commonProviders);
    }
}