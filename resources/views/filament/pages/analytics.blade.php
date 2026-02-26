<x-filament-panels::page>
    @livewire(\App\Filament\Widgets\AnalyticsStatsWidget::class)

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            @livewire(\App\Filament\Widgets\MonthlyRevenueComparisonWidget::class)
        </div>
        <div>
            @livewire(\App\Filament\Widgets\HourlyTrafficWidget::class)
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            @livewire(\App\Filament\Widgets\TopCardsWidget::class)
        </div>
        <div>
            @livewire(\App\Filament\Widgets\BrowserStatsWidget::class)
        </div>
    </div>
</x-filament-panels::page>
