<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Domain\Contacts\UseCases\CreateContactUseCase;
use App\Domain\Contacts\Repositories\ContactRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function __construct(
        private ContactRepositoryInterface $repository
    ) {}

    public function index(): JsonResponse
    {
        return response()->json($this->repository->all());
    }

    public function store(StoreContactRequest $request, CreateContactUseCase $useCase): JsonResponse
    {
        $contact = $useCase->execute($request->validated());

        return response()->json($contact, 201);
    }
}