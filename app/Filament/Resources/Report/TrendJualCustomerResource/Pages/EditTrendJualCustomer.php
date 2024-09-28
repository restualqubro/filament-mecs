<?php

namespace App\Filament\Resources\Report\TrendJualCustomerResource\Pages;

use App\Filament\Resources\Report\TrendJualCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrendJualCustomer extends EditRecord
{
    protected static string $resource = TrendJualCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
