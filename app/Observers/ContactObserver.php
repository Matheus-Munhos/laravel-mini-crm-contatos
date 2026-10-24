<?php

namespace App\Observers;

class ContactObserver
{
    public function saving($contact): void
    {
        if (!empty($contact->phone)) {
            $contact->phone = preg_replace('/\D/', '', $contact->phone);
        }
    }
}