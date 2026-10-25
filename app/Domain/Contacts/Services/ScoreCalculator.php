<?php

namespace App\Domain\Contacts\Services;

use App\Domain\Contacts\Services\Rules\BrazilianDomainRule;
use App\Domain\Contacts\Services\Rules\CorporateEmailRule;
use App\Domain\Contacts\Services\Rules\FullNameRule;
use App\Domain\Contacts\Services\Rules\ScoreRuleInterface;
use App\Domain\Contacts\Services\Rules\SpPhoneRule;
use App\Domain\Contacts\ValueObjects\Email;
use App\Domain\Contacts\ValueObjects\Phone;

class ScoreCalculator
{
    /**
     * @var array<ScoreRuleInterface>
     */
    private array $rules;

    public function __construct(?array $rules = null)
    {
        $this->rules = $rules ?? [
            new CorporateEmailRule(),
            new BrazilianDomainRule(),
            new SpPhoneRule(),
            new FullNameRule(),
        ];
    }

    public function calculate(string $name, Email $email, Phone $phone): int
    {
        $score = 0;

        foreach ($this->rules as $rule) {
            $score += $rule->calculate($name, $email, $phone);
        }

        return $score;
    }
}