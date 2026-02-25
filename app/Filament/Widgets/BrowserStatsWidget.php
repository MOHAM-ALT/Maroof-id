<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\CardView;
use Illuminate\Support\Facades\DB;

class BrowserStatsWidget extends ChartWidget
{
    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = 1;

    public function getHeading(): ?string
    {
        return 'توزيع المتصفحات';
    }

    protected function getData(): array
    {
        $data = CardView::select('browser', DB::raw('COUNT(*) as total'))
            ->whereNotNull('browser')
            ->where('browser', '!=', '')
            ->groupBy('browser')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'الزيارات',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(168, 162, 158, 0.8)',
                    ],
                ],
            ],
            'labels' => $data->pluck('browser')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
