<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\DB;

class MonthlyRevenueComparisonWidget extends ChartWidget
{
    protected static ?int $sort = 10;
    protected int | string | array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return 'مقارنة الإيرادات الشهرية';
    }

    public function getDescription(): ?string
    {
        return 'الشهر الحالي مقارنة بالشهر السابق';
    }

    protected function getData(): array
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $prevMonth = now()->subMonth();

        $currentStart = now()->startOfMonth();
        $currentEnd = now()->endOfMonth();
        $prevStart = $prevMonth->copy()->startOfMonth();
        $prevEnd = $prevMonth->copy()->endOfMonth();

        $currentData = Order::where('payment_status', PaymentStatus::Paid)
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->select(DB::raw("CAST(strftime('%d', created_at) AS INTEGER) as day"), DB::raw('SUM(total) as total'))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $prevData = Order::where('payment_status', PaymentStatus::Paid)
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->select(DB::raw("CAST(strftime('%d', created_at) AS INTEGER) as day"), DB::raw('SUM(total) as total'))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $daysInMonth = now()->daysInMonth;
        $labels = [];
        $currentValues = [];
        $prevValues = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $labels[] = $i;
            $currentValues[] = $currentData[$i] ?? 0;
            $prevValues[] = $prevData[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => now()->translatedFormat('F Y'),
                    'data' => $currentValues,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => $prevMonth->translatedFormat('F Y'),
                    'data' => $prevValues,
                    'borderColor' => 'rgb(156, 163, 175)',
                    'backgroundColor' => 'rgba(156, 163, 175, 0.1)',
                    'borderDash' => [5, 5],
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
