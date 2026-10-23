<?php

namespace Tests\Feature\Api;

use App\Models\Contact;
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

    public function test_pode_atualizar_um_contato_e_recalcular_score(): void
    {
        $contact = Contact::create([
            'name'  => 'Carlos',
            'email' => 'carlos@gmail.com',
            'phone' => '(21) 88888-8888',
            'score' => 0,
        ]);

        $payload = [
            'name'  => 'Carlos Alberto',
            'email' => 'carlos@empresa.com.br',
            'phone' => '(11) 99999-9999',
        ];

        $response = $this->putJson("/api/contacts/{$contact->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name'  => 'Carlos Alberto',
                     'score' => 60,
                 ]);
    }

    public function test_pode_deletar_um_contato(): void
    {
        $contact = Contact::create([
            'name'  => 'Contato Teste',
            'email' => 'teste@gmail.com',
            'phone' => '(11) 99999-9999',
            'score' => 20,
        ]);

        $response = $this->deleteJson("/api/contacts/{$contact->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('contacts', [
            'id' => $contact->id,
        ]);
    }
}