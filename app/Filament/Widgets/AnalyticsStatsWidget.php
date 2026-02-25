<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\CardView;
use App\Models\Order;
use App\Models\Card;
use App\Enums\PaymentStatus;

class AnalyticsStatsWidget extends BaseWidget
{
    protected static ?int $sort = 11;

    protected function getStats(): array
    {
        $totalViews = CardView::count();
        $viewsToday = CardView::whereDate('viewed_at', today())->count();
        $viewsYesterday = CardView::whereDate('viewed_at', today()->subDay())->count();
        $viewsChange = $viewsYesterday > 0
            ? round((($viewsToday - $viewsYesterday) / $viewsYesterday) * 100, 1)
            : 0;

        $avgViewsPerCard = Card::where('is_active', true)->count() > 0
            ? round($totalViews / Card::where('is_active', true)->count(), 1)
            : 0;

        $conversionRate = $totalViews > 0
            ? round((Order::where('payment_status', PaymentStatus::Paid)->count() / $totalViews) * 100, 2)
            : 0;

        $avgOrderValue = Order::where('payment_status', PaymentStatus::Paid)->avg('total') ?? 0;

        return [
            Stat::make('إجمالي المشاهدات', number_format($totalViews))
                ->description("اليوم: {$viewsToday} | التغير: {$viewsChange}%")
                ->descriptionIcon($viewsChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($viewsChange >= 0 ? 'success' : 'danger'),

            Stat::make('متوسط المشاهدات/بطاقة', number_format($avgViewsPerCard, 1))
                ->description('للبطاقات النشطة')
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),

            Stat::make('معدل التحويل', $conversionRate . '%')
                ->description('من مشاهدة إلى طلب مدفوع')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),

            Stat::make('متوسط قيمة الطلب', number_format($avgOrderValue, 2) . ' ر.س')
                ->description('للطلبات المدفوعة')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
