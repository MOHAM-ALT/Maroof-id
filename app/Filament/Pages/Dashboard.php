<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\RevenueChartWidget;
use App\Filament\Widgets\OrdersChartWidget;
use App\Filament\Widgets\TopCountriesWidget;
use App\Filament\Widgets\DeviceStatsWidget;
use App\Filament\Widgets\UserGrowthWidget;
use App\Filament\Widgets\BrowserStatsWidget;
use App\Filament\Widgets\HourlyTrafficWidget;
use App\Filament\Widgets\TopCardsWidget;
use App\Filament\Widgets\MonthlyRevenueComparisonWidget;
use App\Filament\Widgets\AnalyticsStatsWidget;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            RevenueChartWidget::class,
            OrdersChartWidget::class,
            TopCountriesWidget::class,
            DeviceStatsWidget::class,
            UserGrowthWidget::class,
            BrowserStatsWidget::class,
            HourlyTrafficWidget::class,
            TopCardsWidget::class,
            MonthlyRevenueComparisonWidget::class,
            AnalyticsStatsWidget::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 2;
    }
}
