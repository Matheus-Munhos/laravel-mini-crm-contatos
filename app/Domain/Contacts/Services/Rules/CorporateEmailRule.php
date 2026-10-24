<?php

namespace App\Domain\Contacts\Services\Rules;

use App\Domain\Contacts\ValueObjects\Email;
use App\Domain\Contacts\ValueObjects\Phone;

class CorporateEmailRule implements ScoreRuleInterface
{
    public function calculate(string $name, Email $email, Phone $phone): int
    {
        return $email->isCorporate() ? 20 : 0;
    }
}