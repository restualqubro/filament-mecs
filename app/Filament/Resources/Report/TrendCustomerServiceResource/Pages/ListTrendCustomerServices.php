<?php

namespace App\Filament\Resources\Report\TrendCustomerServiceResource\Pages;

use App\Filament\Resources\Report\TrendCustomerServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrendCustomerServices extends ListRecords
{
    protected static string $resource = TrendCustomerServiceResource::class;

    protected function getHeaderActions(): array
    {
        $decodeQueryString = urldecode(request()->getQueryString());
        return [
            Actions\Action::make('export')
                ->label('Export PDF')
                ->url('/print/reporttrendcustomerservices?'. $decodeQueryString)                
                ->openUrlInNewTab()            
        ];
    }
}
