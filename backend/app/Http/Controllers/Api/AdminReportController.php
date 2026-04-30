<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Order;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Http\Response;

class AdminReportController extends Controller
{
    private function escapeCell(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    private function row(array $cells): string
    {
        $xml = '<Row>';
        foreach ($cells as $cell) {
            $xml .= '<Cell><Data ss:Type="String">' . $this->escapeCell((string) $cell) . '</Data></Cell>';
        }
        $xml .= '</Row>';
        return $xml;
    }

    public function excel(): Response
    {
        $users = User::query()->orderBy('id')->get(['id', 'name', 'email', 'role', 'created_at']);
        $cars = Car::query()->orderBy('id')->get(['id', 'name', 'segment', 'transmission', 'fuel', 'rate_minute', 'rate_hour', 'rate_day', 'is_active']);
        $orders = Order::query()->with('car:id,name', 'user:id,name')->orderByDesc('id')->limit(300)->get();
        $documents = UserDocument::query()->with('user:id,name')->orderByDesc('id')->limit(300)->get();

        $rows = [];
        $rows[] = $this->row(['Отчет CarShare', now()->format('d.m.Y H:i')]);
        $rows[] = $this->row(['']);
        $rows[] = $this->row(['Сводка']);
        $rows[] = $this->row(['Пользователи', (string) $users->count()]);
        $rows[] = $this->row(['Автомобили', (string) $cars->count()]);
        $rows[] = $this->row(['Заказы (в отчете)', (string) $orders->count()]);
        $rows[] = $this->row(['Документы (в отчете)', (string) $documents->count()]);

        $rows[] = $this->row(['']);
        $rows[] = $this->row(['Пользователи']);
        $rows[] = $this->row(['ID', 'Имя', 'Email', 'Роль', 'Дата регистрации']);
        foreach ($users as $user) {
            $rows[] = $this->row([
                $user->id,
                $user->name,
                $user->email,
                $user->role,
                optional($user->created_at)->format('d.m.Y H:i'),
            ]);
        }

        $rows[] = $this->row(['']);
        $rows[] = $this->row(['Автомобили']);
        $rows[] = $this->row(['ID', 'Название', 'Сегмент', 'Трансмиссия', 'Топливо', 'Мин', 'Час', 'День', 'Активен']);
        foreach ($cars as $car) {
            $rows[] = $this->row([
                $car->id,
                $car->name,
                $car->segment,
                $car->transmission,
                $car->fuel,
                $car->rate_minute,
                $car->rate_hour,
                $car->rate_day,
                $car->is_active ? 'Да' : 'Нет',
            ]);
        }

        $rows[] = $this->row(['']);
        $rows[] = $this->row(['Заказы (последние 300)']);
        $rows[] = $this->row(['ID', 'Пользователь', 'Авто', 'Тариф', 'Статус', 'Сумма', 'Начало', 'Окончание']);
        foreach ($orders as $order) {
            $rows[] = $this->row([
                $order->id,
                $order->user?->name ?? '—',
                $order->car?->name ?? '—',
                $order->tariff,
                $order->status,
                $order->total_sum ?? 0,
                optional($order->start_at)->format('d.m.Y H:i'),
                optional($order->end_at)->format('d.m.Y H:i'),
            ]);
        }

        $rows[] = $this->row(['']);
        $rows[] = $this->row(['Документы (последние 300)']);
        $rows[] = $this->row(['ID', 'Пользователь', 'Тип', 'Статус', 'Файл', 'Дата']);
        foreach ($documents as $document) {
            $rows[] = $this->row([
                $document->id,
                $document->user?->name ?? '—',
                $document->type,
                $document->status,
                $document->original_name ?? '—',
                optional($document->created_at)->format('d.m.Y H:i'),
            ]);
        }

        $xml = '<?xml version="1.0"?>'
            . '<?mso-application progid="Excel.Sheet"?>'
            . '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" '
            . 'xmlns:o="urn:schemas-microsoft-com:office:office" '
            . 'xmlns:x="urn:schemas-microsoft-com:office:excel" '
            . 'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">'
            . '<Worksheet ss:Name="Report"><Table>'
            . implode('', $rows)
            . '</Table></Worksheet></Workbook>';

        $fileName = 'carshare-report-' . now()->format('Y-m-d-H-i') . '.xls';

        return response($xml, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0, no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}

