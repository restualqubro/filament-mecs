<?php

namespace App\Filament\Resources\Report\TrendJualCustomerResource\Pages;

use App\Filament\Resources\Report\TrendJualCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrendJualCustomers extends ListRecords
{
    protected static string $resource = TrendJualCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
