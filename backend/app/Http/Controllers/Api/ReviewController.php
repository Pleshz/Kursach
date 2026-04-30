<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Car $car)
    {
        $reviews = Review::query()
            ->with('user:id,name')
            ->where('car_id', $car->id)
            ->orderByDesc('created_at')
            ->get(['id', 'car_id', 'user_id', 'rating', 'comment', 'created_at']);

        $average = $reviews->isEmpty() ? null : round($reviews->avg('rating'), 2);

        return response()->json([
            'data' => $reviews,
            'meta' => [
                'count' => $reviews->count(),
                'average' => $average,
            ],
        ]);
    }

    public function store(Request $request, Car $car)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        $userId = $request->user()->id;

        $hasFinishedOrder = Order::query()
            ->where('car_id', $car->id)
            ->where('user_id', $userId)
            ->where('status', 'finished')
            ->exists();

        if (!$hasFinishedOrder) {
            return response()->json([
                'message' => 'Оставить отзыв можно только после завершённой поездки на этом автомобиле.',
            ], 403);
        }

        $alreadyReviewed = Review::query()
            ->where('car_id', $car->id)
            ->where('user_id', $userId)
            ->exists();

        if ($alreadyReviewed) {
            return response()->json([
                'message' => 'Вы уже оставили отзыв на этот автомобиль.',
            ], 409);
        }

        $review = Review::query()->create([
            'car_id' => $car->id,
            'user_id' => $userId,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return response()->json([
            'data' => $review->load('user:id,name'),
        ], 201);
    }
}
