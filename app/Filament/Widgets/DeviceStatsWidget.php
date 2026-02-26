<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DeviceStatsWidget extends ChartWidget
{
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 1;

    public function getHeading(): ?string
    {
        return 'الأجهزة المستخدمة';
    }

    public function getDescription(): ?string
    {
        return 'توزيع الزيارات حسب نوع الجهاز';
    }

    protected function getData(): array
    {
        $data = DB::table('card_views')
            ->select('device_type', DB::raw('COUNT(*) as total'))
            ->whereNotNull('device_type')
            ->where('device_type', '!=', '')
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->get();

        if ($data->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'data' => [1, 1, 1],
                        'backgroundColor' => [
                            'rgba(107, 114, 128, 0.3)',
                            'rgba(107, 114, 128, 0.2)',
                            'rgba(107, 114, 128, 0.1)',
                        ],
                    ],
                ],
                'labels' => ['جوال', 'كمبيوتر', 'تابلت'],
            ];
        }

        $labelMap = [
            'mobile' => 'جوال',
            'desktop' => 'كمبيوتر',
            'tablet' => 'تابلت',
        ];

        $colorMap = [
            'mobile' => 'rgba(59, 130, 246, 0.8)',
            'desktop' => 'rgba(16, 185, 129, 0.8)',
            'tablet' => 'rgba(245, 158, 11, 0.8)',
        ];

        return [
            'datasets' => [
                [
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => $data->pluck('device_type')->map(
                        fn ($type) => $colorMap[$type] ?? 'rgba(139, 92, 246, 0.8)'
                    )->toArray(),
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => $data->pluck('device_type')->map(
                fn ($type) => $labelMap[$type] ?? $type
            )->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'cutout' => '60%',
        ];
    }
}
