<?php

namespace App\Domain\Contacts\UseCases;

use App\Domain\Contacts\Repositories\ContactRepositoryInterface;
use App\Domain\Contacts\ValueObjects\Email;
use App\Domain\Contacts\ValueObjects\Phone;
use App\Events\ContactCreatedEvent;
use App\Models\Contact;

class CreateContactUseCase
{
    public function __construct(
        private ContactRepositoryInterface $repository
    ) {}

    public function execute(array $data): Contact
    {
        $email = new Email($data['email']);
        $phone = new Phone($data['phone']);

        $contact = $this->repository->create([
            'name'   => $data['name'],
            'email'  => $email->getValue(),
            'phone'  => $phone->getValue(),
            'score'  => 0,
            'status' => 'pending',
        ]);

        ContactCreatedEvent::dispatch($contact);

        return $contact;
    }
}