<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserGrowthWidget extends ChartWidget
{
    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return 'نمو المستخدمين';
    }

    public function getDescription(): ?string
    {
        return 'عدد التسجيلات الجديدة خلال آخر 12 شهر';
    }

    protected function getData(): array
    {
        $months = collect();
        $labels = collect();

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = User::whereBetween('created_at', [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth(),
            ])->count();
            $months->push($count);
            $labels->push($date->translatedFormat('M Y'));
        }

        return [
            'datasets' => [
                [
                    'label' => 'مستخدمون جدد',
                    'data' => $months->toArray(),
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                    'pointBackgroundColor' => 'rgb(59, 130, 246)',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.05)',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}
