<?php

namespace App\Jobs;

use App\Domain\Contacts\Services\ScoreCalculator;
use App\Domain\Contacts\ValueObjects\Email;
use App\Domain\Contacts\ValueObjects\Phone;
use App\Events\ContactScoreProcessed;
use App\Models\Contact;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessContactScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $contactId;

    public function __construct(Contact|int $contact)
    {
        $this->contactId = $contact instanceof Contact ? $contact->id : $contact;
    }

    public function handle(ScoreCalculator $calculator): void
    {
        $contact = Contact::find($this->contactId);

        if (!$contact) {
            return;
        }

        try {
            // 1. Altera status para processing
            $contact->update(['status' => 'processing']);

            // 2. Simula carga de processamento assíncrono
            sleep(1);

            // 3. Calcula score com Domain Service (Strategy)
            $score = $calculator->calculate(
                $contact->name,
                new Email($contact->email),
                new Phone($contact->phone)
            );

            // 4. Salva score, status active e processed_at
            $contact->update([
                'score' => $score,
                'status' => 'active',
                'processed_at' => now(),
            ]);

            // 5. Dispara evento de domínio
            event(new ContactScoreProcessed($contact));

        } catch (Exception $e) {
            $contact->update(['status' => 'failed']);
            throw $e;
        }
    }
}