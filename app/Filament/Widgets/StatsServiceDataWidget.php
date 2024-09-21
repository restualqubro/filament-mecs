<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Service\Data;

class StatsServiceDataWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $servicePending = Data::where('status', 'Baru')->where('status', 'Proses')->count();
        $serviceCancel = Data::where('status', 'Cancel')->count();
        $serviceSelesai = Data::where('status', 'Selesai')->where('status', 'Selesai')->count();

        return [
            Stat::make('Service Pending', $servicePending),                
            Stat::make('Service Cancel', $serviceCancel),                
            Stat::make('Service Selesai', $serviceSelesai),                
        ];
    }   
}
