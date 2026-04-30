<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminDocumentController extends Controller
{
    public function requests()
    {
        $pendingDocs = UserDocument::query()
            ->with('user:id,name,email')
            ->where('status', 'uploaded')
            ->orderByDesc('created_at')
            ->get();

        $requests = $pendingDocs
            ->groupBy('user_id')
            ->map(function ($docs, $userId) {
                $first = $docs->first();
                return [
                    'user_id' => (int) $userId,
                    'user_name' => $first?->user?->name ?? '—',
                    'user_email' => $first?->user?->email ?? '—',
                    'documents_count' => $docs->count(),
                    'submitted_at' => optional($docs->max('created_at'))->toISOString(),
                ];
            })
            ->values();

        return response()->json([
            'data' => $requests,
        ]);
    }

    public function userDocuments(int $userId)
    {
        $docs = UserDocument::query()
            ->with('user:id,name,email')
            ->where('user_id', $userId)
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'data' => $docs->map(function (UserDocument $d) {
                return [
                    'id' => $d->id,
                    'user_id' => $d->user_id,
                    'user_name' => $d->user?->name,
                    'user_email' => $d->user?->email,
                    'type' => $d->type,
                    'status' => $d->status,
                    'original_name' => $d->original_name,
                    'size' => $d->size,
                    'mime' => $d->mime,
                    'url' => asset('storage/' . ltrim($d->path, '/')),
                    'created_at' => optional($d->created_at)->toISOString(),
                ];
            }),
        ]);
    }

    public function decide(Request $request, UserDocument $document)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['verified', 'rejected'])],
        ]);

        $document->status = $validated['status'];
        $document->save();

        return response()->json([
            'data' => [
                'id' => $document->id,
                'status' => $document->status,
            ],
        ]);
    }
}

