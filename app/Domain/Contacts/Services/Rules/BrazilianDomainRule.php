<?php

namespace App\Domain\Contacts\Services\Rules;

use App\Domain\Contacts\ValueObjects\Email;
use App\Domain\Contacts\ValueObjects\Phone;

class BrazilianDomainRule implements ScoreRuleInterface
{
    public function calculate(string $name, Email $email, Phone $phone): int
    {
        return str_ends_with(strtolower($email->getValue()), '.br') ? 20 : 0;
    }
}