<?php

namespace App\Domain\Contacts\Services;

use App\Domain\Contacts\ValueObjects\Email;
use App\Domain\Contacts\ValueObjects\Phone;

class ScoreCalculator
{
    public function calculate(string $name, Email $email, Phone $phone): int
    {
        $score = 0;

        // 1. E-mail corporativo 
        if ($email->isCorporate()) {
            $score += 20;
        }

        // 2. E-mail com domínio .br 
        if ($email->isBr()) {
            $score += 10;
        }

        // 3. Nome completo (mais de uma palavra) 
        $nameParts = array_filter(explode(' ', trim($name)));
        if (count($nameParts) > 1) {
            $score += 10;
        }

        // 4. Telefone com DDD de São Paulo (11 a 19) 
        if ($phone->isSaoPaulo()) {
            $score += 20;
        }

        return $score;
    }
}