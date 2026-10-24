<?php

namespace App\Domain\Contacts\Services\Rules;

use App\Domain\Contacts\ValueObjects\Email;
use App\Domain\Contacts\ValueObjects\Phone;

class FullNameRule implements ScoreRuleInterface
{
    public function calculate(string $name, Email $email, Phone $phone): int
    {
        $parts = explode(' ', trim($name));
        return count($parts) >= 2 ? 20 : 0;
    }
}