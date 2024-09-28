<?php

namespace App\Filament\Resources\Report\TrendServiceCustomerResource\Pages;

use App\Filament\Resources\Report\TrendServiceCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrendServiceCustomer extends EditRecord
{
    protected static string $resource = TrendServiceCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
