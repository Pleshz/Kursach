<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class AdminCarController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Car::query()->orderByDesc('id')->get(),
        ]);
    }

    public function show(Car $car)
    {
        return response()->json([
            'data' => $car,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => ['nullable', 'string', 'max:255', 'unique:cars,slug'],
            'name' => ['required', 'string', 'max:255'],
            'segment' => ['required', 'string', 'max:255'],
            'transmission' => ['required', 'string', 'max:255'],
            'fuel' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'rate_minute' => ['required', 'numeric', 'min:0'],
            'rate_hour' => ['required', 'numeric', 'min:0'],
            'rate_day' => ['required', 'numeric', 'min:0'],
            'range_km' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $car = Car::query()->create($validated);

        return response()->json([
            'data' => $car,
        ], 201);
    }

    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'slug' => ['nullable', 'string', 'max:255', 'unique:cars,slug,' . $car->id],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'segment' => ['sometimes', 'required', 'string', 'max:255'],
            'transmission' => ['sometimes', 'required', 'string', 'max:255'],
            'fuel' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'rate_minute' => ['sometimes', 'required', 'numeric', 'min:0'],
            'rate_hour' => ['sometimes', 'required', 'numeric', 'min:0'],
            'rate_day' => ['sometimes', 'required', 'numeric', 'min:0'],
            'range_km' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $car->fill($validated);
        $car->save();

        return response()->json([
            'data' => $car,
        ]);
    }

    public function destroy(Car $car)
    {
        $car->delete();

        return response()->json([
            'message' => 'Автомобиль удален.',
        ]);
    }
}

