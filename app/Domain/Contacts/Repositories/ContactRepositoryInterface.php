<?php

namespace App\Domain\Contacts\Repositories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;

interface ContactRepositoryInterface
{
    public function all(): Collection;
    public function findById(int $id): ?Contact;
    public function create(array $data): Contact;
    public function update(int $id, array $data): Contact;
    public function delete(int $id): bool;
}