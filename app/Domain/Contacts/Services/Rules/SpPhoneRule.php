<?php

namespace App\Domain\Contacts\Services\Rules;

use App\Domain\Contacts\ValueObjects\Email;
use App\Domain\Contacts\ValueObjects\Phone;

class SpPhoneRule implements ScoreRuleInterface
{
    public function calculate(string $name, Email $email, Phone $phone): int
    {
        // Remove quaisquer caracteres não numéricos
        $digits = preg_replace('/\D/', '', $phone->getValue());

        if (strlen($digits) < 2) {
            return 0;
        }

        $ddd = (int) substr($digits, 0, 2);

        // DDDs válidos do estado de São Paulo: 11 a 19
        if ($ddd >= 11 && $ddd <= 19) {
            return 20;
        }

        // DDDs válidos dos outros estados do Brasil
        $outrosDddsValidos = [
            21, 22, 24, 27, 28, // RJ / ES
            31, 32, 33, 34, 35, 37, 38, // MG
            41, 42, 43, 44, 45, 46, 47, 48, 49, // PR / SC
            51, 53, 54, 55, // RS
            61, 62, 64, 65, 66, 67, 68, 69, // DF / GO / MT / MS / AC / RO
            71, 73, 74, 75, 77, 79, // BA / SE
            81, 82, 83, 84, 85, 86, 87, 88, 89, // PE / AL / PB / RN / CE / PI
            91, 92, 93, 94, 95, 96, 97, 98, 99 // PA / AM / RR / AP / MA
        ];

        if (in_array($ddd, $outrosDddsValidos, true)) {
            return 10;
        }

        return 0;
    }
}