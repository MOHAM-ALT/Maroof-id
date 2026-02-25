<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\CardView;
use Illuminate\Support\Facades\DB;

class HourlyTrafficWidget extends ChartWidget
{
    protected static ?int $sort = 8;
    protected int | string | array $columnSpan = 1;

    public function getHeading(): ?string
    {
        return 'حركة الزوار حسب الساعة';
    }

    public function getDescription(): ?string
    {
        return 'آخر 7 أيام';
    }

    protected function getData(): array
    {
        $data = CardView::where('viewed_at', '>=', now()->subDays(7))
            ->select(DB::raw('CAST(strftime("%H", viewed_at) AS INTEGER) as hour'), DB::raw('COUNT(*) as total'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('total', 'hour')
            ->toArray();

        $hours = [];
        $values = [];
        for ($i = 0; $i < 24; $i++) {
            $hours[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            $values[] = $data[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'الزيارات',
                    'data' => $values,
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $hours,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
