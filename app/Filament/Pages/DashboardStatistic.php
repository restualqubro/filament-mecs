<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class DashboardStatistic extends BaseDashboard
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $navigationLabel = 'Dashboard Statistic';

    protected static string $view = 'filament.pages.dashboard-statistic';

    protected function getHeaderWidgets(): array
    {
        return [            
            Widgets\StatsCustomerUmumWidget::class,
            Widgets\StatsServiceDataWidget::class,
            Widgets\CustomerWidget::class,
            Widgets\ServiceDataWidget::class,
            Widgets\PiutangServiceTableWidget::class,
            Widgets\PiutangJualTableWidget::class,
            
        ];
    }
}
