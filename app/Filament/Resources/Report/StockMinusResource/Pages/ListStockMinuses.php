<?php

namespace App\Filament\Resources\Report\StockMinusResource\Pages;

use App\Filament\Resources\Report\StockMinusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStockMinuses extends ListRecords
{
    protected static string $resource = StockMinusResource::class;

    protected function getHeaderActions(): array
    {
        $decodeQueryString = urldecode(request()->getQueryString());
        return [
            Actions\Action::make('export')
                ->label('Export PDF')
                ->url('/print/reportstockminus?'. $decodeQueryString)                
                ->openUrlInNewTab()            
        ];
    }
}
