<?php

namespace App\Domain\Contacts\Services\Rules;

use App\Domain\Contacts\ValueObjects\Email;
use App\Domain\Contacts\ValueObjects\Phone;

class FullNameRule implements ScoreRuleInterface
{
    public function calculate(string $name, Email $email, Phone $phone): int
    {
        $words = preg_split('/\s+/', trim($name));

        return count($words) > 1 ? 10 : 0;
    }
}