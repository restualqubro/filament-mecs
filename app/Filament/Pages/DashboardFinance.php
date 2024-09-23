<?php

namespace App\Filament\Pages;

use App\Filament\Widgets;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Dashboard as BaseDashboard;

class DashboardFinance extends BaseDashboard
{        
    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $navigationLabel = 'Dashboard Finance';

    protected static string $view = 'filament.pages.dashboard-finance';

    protected function getHeaderWidgets(): array
    {
        return [
            Widgets\OmzetPenjualanWidget::class,
            Widgets\StatsFinanceWidget::class,
            Widgets\PiutangBeliTableWidget::class,
        ];
    }
    
}
