<?php

namespace App\Filament\Resources\Services\InvoiceResource\Pages;

use App\Filament\Resources\Services\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mutateFormDataBeforeFill(array $data): array
    {
        $data['sisa'] = (int)str_replace('.', '', $data['sisa']);
        $data['service_id'] = $data['id'];
        return dd($data);
    }
}
