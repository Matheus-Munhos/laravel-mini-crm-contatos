<?php

namespace App\Domain\Contacts\Services\Rules;

use App\Domain\Contacts\ValueObjects\Email;
use App\Domain\Contacts\ValueObjects\Phone;

class SpPhoneRule implements ScoreRuleInterface
{
    public function calculate(string $name, Email $email, Phone $phone): int
    {
        // +20 pontos: DDD de São Paulo (11 a 19)
        if ($phone->isSaoPaulo()) {
            return 20;
        }

        // +10 pontos: Outros estados válidos
        return 10;
    }
}