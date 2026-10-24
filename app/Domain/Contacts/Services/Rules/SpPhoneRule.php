<?php

namespace App\Domain\Contacts\Services\Rules;

use App\Domain\Contacts\ValueObjects\Email;
use App\Domain\Contacts\ValueObjects\Phone;

class SpPhoneRule implements ScoreRuleInterface
{
    public function calculate(string $name, Email $email, Phone $phone): int
    {
        return $phone->isFromSaoPaulo() ? 20 : 0;
    }
}