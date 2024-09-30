<?php

namespace App\Filament\Resources\Report\TrendCustomerServiceResource\Pages;

use App\Filament\Resources\Report\TrendCustomerServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrendCustomerService extends EditRecord
{
    protected static string $resource = TrendCustomerServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
