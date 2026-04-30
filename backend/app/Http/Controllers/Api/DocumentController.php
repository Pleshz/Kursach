<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $docs = UserDocument::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('id')
            ->get([
                'id',
                'user_id',
                'type',
                'path',
                'original_name',
                'size',
                'mime',
                'status',
                'created_at',
            ]);

        return response()->json([
            'data' => $docs->map(function (UserDocument $d) {
                return [
                    'id' => $d->id,
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:passport,license,selfie'],
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $file = $validated['file'];
        $path = $file->store('documents', 'public');

        $doc = UserDocument::create([
            'user_id' => $request->user()->id,
            'type' => $validated['type'],
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime' => $file->getMimeType(),
            'status' => 'uploaded',
        ]);

        return response()->json([
            'data' => [
                'id' => $doc->id,
                'type' => $doc->type,
                'status' => $doc->status,
                'url' => asset('storage/' . ltrim($doc->path, '/')),
            ],
        ], 201);
    }
}

