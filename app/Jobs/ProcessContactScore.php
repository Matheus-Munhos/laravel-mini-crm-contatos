<?php

namespace App\Jobs;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessContactScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Cria uma nova instância do Job.
     */
    public function __construct(public Contact $contact)
    {
    }

    /**
     * Executa o Job na fila.
     */
    public function handle(): void
    {
        // Simula um processamento assíncrono (ex: envio de email de boas-vindas)
        Log::info("Processamento em fila executado com sucesso para o contacto: {$this->contact->email}");
    }
}