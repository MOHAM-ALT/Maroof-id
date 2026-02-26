<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\AnalyticsStatsWidget;
use App\Filament\Widgets\MonthlyRevenueComparisonWidget;
use App\Filament\Widgets\TopCardsWidget;
use App\Filament\Widgets\BrowserStatsWidget;
use App\Filament\Widgets\HourlyTrafficWidget;

class Analytics extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationLabel = 'التحليلات';
    protected static ?string $title = 'التحليلات التفصيلية';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.analytics';

    public function getWidgets(): array
    {
        return [
            AnalyticsStatsWidget::class,
            MonthlyRevenueComparisonWidget::class,
            TopCardsWidget::class,
            HourlyTrafficWidget::class,
            BrowserStatsWidget::class,
        ];
    }

    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }

    public function getColumns(): int | string | array
    {
        return 2;
    }
}
