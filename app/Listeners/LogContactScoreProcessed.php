<?php

namespace App\Listeners;

use App\Events\ContactScoreProcessed;
use Illuminate\Support\Facades\File;

class LogContactScoreProcessed
{
    public function handle(ContactScoreProcessed $event): void
    {
        $contact = $event->contact;
        $logPath = storage_path('logs/contact.log');

        $message = sprintf(
            "[%s] ID: %d | Email: %s | Novo Score: %d | Status: %s" . PHP_EOL,
            now()->toDateTimeString(),
            $contact->id,
            $contact->email,
            $contact->score,
            $contact->status
        );

        File::append($logPath, $message);
    }
}