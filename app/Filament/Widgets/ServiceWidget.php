<?php

namespace App\Filament\Widgets;

use App\Models\Service\DetailComponent;
use App\Models\Service\DetailService;
use App\Models\Service\Invoice;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ServiceWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $detailServiceSum = DetailService::whereHas('selesai', function($q) {
                                $q->whereHas('invoice', function($q) {
                                    $q->where('status', '!=', 'Piutang');
                                });
                            })->sum(DB::raw('service_qty * (biaya - service_disc)'));

        $detailComponentSum = DetailComponent::whereHas('selesai', function($q) {
                                $q->whereHas('invoice', function($q) {
                                    $q->where('status', '!=', 'Piutang');
                                });
                            })->sum(DB::raw('component_qty * hbeli'));

        $piutangServiceSum = Invoice::where('status' , '=', 'Piutang')->sum('sisa');
        return [
            Stat::make('Omzet Service', number_format($detailServiceSum, 0, '', '.')),
            Stat::make('Profit Service', number_format(($detailServiceSum - $detailComponentSum) , 0, '', '.')),
            Stat::make('Piutang Service', number_format($piutangServiceSum, 0, '', '.')),
        ];
    }
}
