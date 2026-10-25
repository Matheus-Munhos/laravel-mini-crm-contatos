<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessContactScore;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;

class ProcessContactScoreController extends Controller
{
    public function __invoke(int $id): JsonResponse
    {
        $contact = Contact::findOrFail($id);

        ProcessContactScore::dispatch($contact->id);

        return response()->json([
            'message' => 'Processamento de score enfileirado com sucesso.',
            'contact_id' => $contact->id,
            'status' => 'queued',
        ], 202);
    }
}