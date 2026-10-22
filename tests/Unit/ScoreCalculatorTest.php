<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Domain\Contacts\ValueObjects\Email;
use App\Domain\Contacts\ValueObjects\Phone;
use App\Domain\Contacts\Services\ScoreCalculator;

class ScoreCalculatorTest extends TestCase
{
    public function test_calcula_score_corretamente_para_email_corporativo_telefone_sp_e_nome_completo()
    {
        // Regras 
        // - E-mail corporativo: 
        // - E-mail .br: 
        // - Nome completo: 
        // - Telefone DDD São Paulo (11 a 19): 
      

        $email = new Email('desenvolvedor@empresa.com.br');
        $phone = new Phone('(11) 99999-9999');
        $name = 'João Silva';

        $calculator = new ScoreCalculator();
        $score = $calculator->calculate($name, $email, $phone);

        $this->assertEquals(60, $score);
    }
}