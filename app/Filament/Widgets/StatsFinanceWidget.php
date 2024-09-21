<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsFinanceWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // PENJUALAN
            Stat::make('Omzet Penjualan', "1.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),                
            Stat::make('Profit Penjualan', "20.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),                
            Stat::make('Piutang Penjualan', "30.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),     
            // SERVICE
            Stat::make('Omzet Service', "1.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),                
            Stat::make('Profit Service', "20.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),                
            Stat::make('Piutang Service', "30.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),                         
            // Transaksi Toko
            Stat::make('Pengeluaran Toko', "1.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),                
            Stat::make('Pembelian Toko', "20.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),                
            Stat::make('Utang Pembelian', "30.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),     
            // PTAM
            Stat::make('Saldo Cash', "1.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),                
            Stat::make('Saldo Rekening Mandiri', "20.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),                
            Stat::make('Penarikan Tunai', "30.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),     
            // NON PROFIT ZONE
            Stat::make('Kompensasi', "1.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),                
            Stat::make('Kerugian Item', "20.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),                
            Stat::make('Retur Service & Penjualan', "30.000.000")
                ->description('500.000 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
