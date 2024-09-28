<?php

namespace App\Filament\Resources\Report\ServiceDataResource\Pages;

use App\Filament\Resources\Report\ServiceDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceData extends ListRecords
{
    protected static string $resource = ServiceDataResource::class;

    protected function getHeaderActions(): array
    {
        $decodeQueryString = urldecode(request()->getQueryString());
        return [
            Actions\Action::make('export')
                ->label('Export PDF')
                ->url('/print/reportservicedata?'. $decodeQueryString)                
                ->openUrlInNewTab()            
        ];
    }
}
