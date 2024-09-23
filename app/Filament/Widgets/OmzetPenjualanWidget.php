<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi\DetailJual;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OmzetPenjualanWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Omzet Penjualan', number_format(DetailJual::all()->sum('profit'), 0, '', '.'))
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),                
        ];
    }
}
