<?php

namespace App\Filament\Resources\Services\ServiceSelesaiResource\Pages;

use App\Filament\Resources\Services\ServiceSelesaiResource;
use Filament\Actions;
use App\Models\Service\Data;
use App\Models\User;
use Filament\Resources\Pages\EditRecord;

class EditServiceSelesai extends EditRecord
{
    protected static string $resource = ServiceSelesaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {        
        $service = Data::where('id', $data['service_id'])->first();
        $teknisi = User::where('id', $data['teknisi_id'])->first();
        $data['teknisi'] = $teknisi->name;
        $data['name'] = $service->customer->name;
        $data['merk'] = $service->merk;
        $data['seri'] = $service->seri;
        
        return $data;
    }
}
