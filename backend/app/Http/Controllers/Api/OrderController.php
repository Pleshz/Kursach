<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Order;
use App\Models\UserDocument;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private function hasVerifiedDocuments(int $userId): bool
    {
        $requiredTypes = ['passport', 'license', 'selfie'];

        $verifiedTypes = UserDocument::query()
            ->where('user_id', $userId)
            ->where('status', 'verified')
            ->whereIn('type', $requiredTypes)
            ->distinct()
            ->pluck('type')
            ->all();

        return count(array_intersect($requiredTypes, $verifiedTypes)) === count($requiredTypes);
    }

    private function syncOrderStatus(Order $order, Carbon $now): void
    {
        if ($order->status === 'cancelled' || !$order->start_at || !$order->end_at) {
            return;
        }

        $nextStatus = 'created';
        if ($order->end_at->lte($now)) {
            $nextStatus = 'finished';
        } elseif ($order->start_at->lte($now)) {
            $nextStatus = 'active';
        }

        if ($order->status !== $nextStatus) {
            $order->status = $nextStatus;
            $order->save();
        }
    }

    private function calculateTotalSum(Car $car, string $tariff, Carbon $startAt, Carbon $endAt): float
    {
        $seconds = max(0, $startAt->diffInSeconds($endAt));
        $minutes = (int) ceil($seconds / 60);
        $hours = (int) ceil($seconds / 3600);
        $days = (int) ceil($seconds / 86400);

        if ($tariff === 'hour') {
            return round($hours * (float) $car->rate_hour, 2);
        }

        if ($tariff === 'day') {
            return round($days * (float) $car->rate_day, 2);
        }

        return round($minutes * (float) $car->rate_minute, 2);
    }

    public function index(Request $request)
    {
        $orders = Order::query()
            ->with('car')
            ->where('user_id', $request->user()->id)
            ->orderByDesc('start_at')
            ->get();
        $now = Carbon::now();
        $orders->each(function (Order $order) use ($now) {
            $this->syncOrderStatus($order, $now);
        });

        return response()->json([
            'data' => $orders,
        ]);
    }

    public function store(Request $request)
    {
        if (!$this->hasVerifiedDocuments($request->user()->id)) {
            return response()->json([
                'message' => 'Оформление заказа доступно только после подтверждения паспорта, водительского удостоверения и селфи с документом.',
            ], 403);
        }

        $validated = $request->validate([
            'car_id' => ['required', 'integer', 'exists:cars,id'],
            'tariff' => ['nullable', 'in:minute,hour,day'],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after:start_at'],
            'end_zone' => ['nullable', 'string', 'max:255'],
        ]);

        $car = Car::query()->where('is_active', true)->findOrFail($validated['car_id']);
        $startAt = Carbon::parse($validated['start_at']);
        $endAt = Carbon::parse($validated['end_at']);
        $tariff = $validated['tariff'] ?? 'minute';

        if ($endAt->isPast()) {
            return response()->json([
                'message' => 'Время окончания аренды должно быть в будущем.',
            ], 422);
        }

        $hasOverlap = Order::query()
            ->where('car_id', $car->id)
            ->whereIn('status', ['created', 'active'])
            ->whereNotNull('start_at')
            ->whereNotNull('end_at')
            ->where(function ($query) use ($startAt, $endAt) {
                $query
                    ->where('start_at', '<', $endAt->toDateTimeString())
                    ->where('end_at', '>', $startAt->toDateTimeString());
            })
            ->exists();

        if ($hasOverlap) {
            return response()->json([
                'message' => 'Этот автомобиль уже забронирован на выбранный период.',
            ], 409);
        }

        $order = Order::query()->create([
            'car_id' => $car->id,
            'user_id' => $request->user()->id,
            'tariff' => $tariff,
            'start_at' => $startAt->toDateTimeString(),
            'end_at' => $endAt->toDateTimeString(),
            'start_at_text' => $startAt->format('d.m.Y H:i'),
            'end_zone' => $validated['end_zone'] ?? null,
            'total_sum' => $this->calculateTotalSum($car, $tariff, $startAt, $endAt),
            'status' => $startAt->isFuture() ? 'created' : 'active',
        ]);

        return response()->json([
            'data' => $order->load('car'),
        ], 201);
    }

    public function show(Order $order)
    {
        if ($order->user_id !== request()->user()->id) {
            abort(403, 'Недостаточно прав для просмотра этого заказа.');
        }
        $this->syncOrderStatus($order, Carbon::now());

        return response()->json([
            'data' => $order->load('car'),
        ]);
    }
}

