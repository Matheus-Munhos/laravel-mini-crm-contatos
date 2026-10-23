<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Contacts\Repositories\ContactRepositoryInterface;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;

class ContactEloquentRepository implements ContactRepositoryInterface
{
    public function all(): Collection
    {
        return Contact::all();
    }

    public function findById(int $id): ?Contact
    {
        return Contact::find($id);
    }

    public function create(array $data): Contact
    {
        return Contact::create($data);
    }

    public function update(int $id, array $data): Contact
    {
        $contact = Contact::findOrFail($id);
        $contact->update($data);
        return $contact;
    }

    public function delete(int $id): bool
    {
        $contact = Contact::findOrFail($id);
        return $contact->delete();
    }
}