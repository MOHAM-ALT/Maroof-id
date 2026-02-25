<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Card;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        // حسابات بسيطة وواضحة
        $totalUsers = User::count();
        $usersThisMonth = User::where('created_at', '>=', now()->startOfMonth())->count();
        $usersGrowth = $totalUsers > 0 ? round(($usersThisMonth / $totalUsers) * 100, 1) : 0;

        $totalCards = Card::count();
        $activeCards = Card::where('is_active', true)->count();

        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', OrderStatus::Pending)->count();

        $totalRevenue = Order::where('payment_status', PaymentStatus::Paid)->sum('total');
        $revenueThisMonth = Order::where('payment_status', PaymentStatus::Paid)
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('total');

        return [
            Stat::make('إجمالي المستخدمين', number_format($totalUsers))
                ->description("زيادة {$usersGrowth}% هذا الشهر")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3])
                ->color('success'),

            Stat::make('البطاقات', number_format($totalCards))
                ->description("{$activeCards} نشطة")
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('info'),

            Stat::make('الطلبات', number_format($totalOrders))
                ->description("{$pendingOrders} قيد الانتظار")
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),

            Stat::make('الإيرادات', number_format($totalRevenue, 2) . ' ر.س')
                ->description(number_format($revenueThisMonth, 2) . ' ر.س هذا الشهر')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
