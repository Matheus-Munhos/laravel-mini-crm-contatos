<?php

namespace App\Domain\Contacts\UseCases;

use App\Domain\Contacts\Repositories\ContactRepositoryInterface;
use App\Domain\Contacts\Services\ScoreCalculator;
use App\Domain\Contacts\ValueObjects\Email;
use App\Domain\Contacts\ValueObjects\Phone;
use App\Models\Contact;

class CreateContactUseCase
{
    public function __construct(
        private ContactRepositoryInterface $repository,
        private ScoreCalculator $scoreCalculator
    ) {}

    public function execute(array $data): Contact
    {
        $email = new Email($data['email']);
        $phone = new Phone($data['phone']);

        $score = $this->scoreCalculator->calculate($data['name'], $email, $phone);

        return $this->repository->create([
            'name' => $data['name'],
            'email' => $email->getValue(),
            'phone' => $phone->getValue(),
            'score' => $score,
        ]);
    }
}