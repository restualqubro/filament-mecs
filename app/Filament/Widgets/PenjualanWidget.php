<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi\DetailJual;
use App\Models\Transaksi\Jual;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class PenjualanWidget extends BaseWidget
{
    use HasWidgetShield;
    protected function getStats(): array
    {
        return [
            Stat::make('Omzet Penjualan', number_format(DetailJual::whereHas('jual', function ($q) {
                $q->where('is_pending', '=', 0);
            })->sum(DB::raw('qty * (hjual - disc)')), 0, '', '.')),   
            Stat::make('Profit Penjualan',  number_format(
                DetailJual::whereHas('jual', function ($q) {
                    $q->where('is_pending', '=', 0);
                })->sum('profit'), 0, '', '.')
            ),             
            Stat::make('Piutang Penjualan', number_format(Jual::where('is_pending', 0)->where('status', 'piutang')->sum('sisa'), 0, '', '.')),
        ];
    }
}
