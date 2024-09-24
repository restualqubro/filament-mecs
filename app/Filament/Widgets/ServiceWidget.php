<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ServiceWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Omzet Service', "1.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),                
            Stat::make('Profit Service', "20.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),                
            Stat::make('Piutang Service', "30.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
