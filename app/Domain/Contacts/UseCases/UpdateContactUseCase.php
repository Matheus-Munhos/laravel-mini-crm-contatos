<?php

namespace App\Domain\Contacts\UseCases;

use App\Domain\Contacts\Repositories\ContactRepositoryInterface;
use App\Domain\Contacts\Services\ScoreCalculator;
use App\Domain\Contacts\ValueObjects\Email;
use App\Domain\Contacts\ValueObjects\Phone;
use App\Models\Contact;

class UpdateContactUseCase
{
    public function __construct(
        private ContactRepositoryInterface $repository,
        private ScoreCalculator $scoreCalculator
    ) {}

    public function execute(int $id, array $data): Contact
    {
        $contact = $this->repository->findById($id);

        if (!$contact) {
            throw new \Exception("Contacto não encontrado.");
        }

        $name = $data['name'] ?? $contact->name;
        $emailStr = $data['email'] ?? $contact->email;
        $phoneStr = $data['phone'] ?? $contact->phone;

        $email = new Email($emailStr);
        $phone = new Phone($phoneStr);

        $score = $this->scoreCalculator->calculate($name, $email, $phone);

        return $this->repository->update($id, [
            'name'  => $name,
            'email' => $email->getValue(),
            'phone' => $phone->getValue(),
            'score' => $score,
        ]);
    }
}