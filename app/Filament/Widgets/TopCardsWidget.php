<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Card;

class TopCardsWidget extends ChartWidget
{
    protected static ?int $sort = 9;
    protected int | string | array $columnSpan = 1;

    public function getHeading(): ?string
    {
        return 'أكثر البطاقات مشاهدة';
    }

    protected function getData(): array
    {
        $cards = Card::where('is_active', true)
            ->orderByDesc('views_count')
            ->limit(10)
            ->get(['title', 'views_count']);

        return [
            'datasets' => [
                [
                    'label' => 'المشاهدات',
                    'data' => $cards->pluck('views_count')->toArray(),
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(20, 184, 166, 0.8)',
                        'rgba(249, 115, 22, 0.8)',
                        'rgba(99, 102, 241, 0.8)',
                        'rgba(168, 162, 158, 0.8)',
                    ],
                ],
            ],
            'labels' => $cards->pluck('title')->map(fn ($t) => mb_substr($t, 0, 15))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
