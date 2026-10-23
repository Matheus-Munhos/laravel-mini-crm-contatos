<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_criar_um_contato_via_api_com_calculo_de_score(): void
    {
        $payload = [
            'name'  => 'João Silva',
            'email' => 'desenvolvedor@empresa.com.br',
            'phone' => '(11) 99999-9999',
        ];

        $response = $this->postJson('/api/contacts', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'name'  => 'João Silva',
                     'score' => 60,
                 ]);

        $this->assertDatabaseHas('contacts', [
            'email' => 'desenvolvedor@empresa.com.br',
            'score' => 60,
        ]);
    }
}