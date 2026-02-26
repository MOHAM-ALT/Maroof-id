<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use App\Enums\OrderStatus;

class OrdersChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 1;

    public function getHeading(): ?string
    {
        return 'الطلبات حسب الحالة';
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'الطلبات',
                    'data' => [
                        Order::where('status', OrderStatus::Pending)->count(),
                        Order::where('status', OrderStatus::Processing)->count(),
                        Order::where('status', OrderStatus::Completed)->count(),
                        Order::where('status', OrderStatus::Cancelled)->count(),
                    ],
                    'backgroundColor' => [
                        'rgb(245, 158, 11)',
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(239, 68, 68)',
                    ],
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => ['معلق', 'قيد المعالجة', 'مكتمل', 'ملغي'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
