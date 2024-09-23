<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class DashboardStatistic extends Page
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
            Widgets\PiutangServiceTableWidget::class,
            Widgets\PiutangJualTableWidget::class,
        ];
    }
}
