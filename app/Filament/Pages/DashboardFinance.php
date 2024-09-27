<?php

namespace App\Filament\Pages;

use App\Filament\Widgets;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

class DashboardFinance extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $navigationLabel = 'Dashboard Finance';

    protected static string $view = 'filament.pages.dashboard-finance';

    protected function getHeaderWidgets(): array
    {
        return [
            Widgets\FinanceWidget::class,
            Widgets\OmzetChart::class,                        
            Widgets\ProfitChart::class,                        
        ];
    }
    
}
