<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())
            ->whereIn('status', ['SUCCESS', 'PAID', 'PROCESSING'])
            ->sum('grand_total');
        $successRate = Order::whereDate('created_at', '>=', now()->subDays(7))
            ->where('status', 'SUCCESS')
            ->count() / max(Order::whereDate('created_at', '>=', now()->subDays(7))->count(), 1) * 100;

        return [
            Stat::make('Today\'s Orders', $todayOrders)
                ->description('New orders today')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Today\'s Revenue', 'Rp ' . number_format($todayRevenue, 0, ',', '.'))
                ->description('Total revenue today')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
            Stat::make('Success Rate', number_format($successRate, 1) . '%')
                ->description('Last 7 days')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($successRate > 80 ? 'success' : 'warning'),
        ];
    }
}