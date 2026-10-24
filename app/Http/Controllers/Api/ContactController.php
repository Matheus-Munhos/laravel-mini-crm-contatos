<?php

namespace App\Http\Controllers\Api;

use App\Domain\Contacts\Repositories\ContactRepositoryInterface;
use App\Domain\Contacts\UseCases\CreateContactUseCase;
use App\Domain\Contacts\UseCases\UpdateContactUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Resources\ContactResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(
        private ContactRepositoryInterface $repository
    ) {}

    public function index(): JsonResponse
    {
        $contacts = $this->repository->all();

        return ContactResource::collection($contacts)->response();
    }

    public function store(StoreContactRequest $request, CreateContactUseCase $useCase): JsonResponse
    {
        $contact = $useCase->execute($request->validated());

        return (new ContactResource($contact))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): JsonResponse
{
    $contact = $this->repository->findById($id);

    if (!$contact) {
        return response()->json(['message' => 'Contacto não encontrado'], 404);
    }

    return (new ContactResource($contact))->response();
}

    public function update(Request $request, int $id, UpdateContactUseCase $useCase): JsonResponse
    {
        $contact = $useCase->execute($id, $request->all());

        return (new ContactResource($contact))->response();
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->repository->delete($id);

        if (!$deleted) {
            return response()->json(['message' => 'Contacto não encontrado'], 404);
        }

        return response()->json(null, 204);
    }
}