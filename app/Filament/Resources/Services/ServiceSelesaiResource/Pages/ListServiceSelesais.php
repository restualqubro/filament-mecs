<?php

namespace App\Filament\Resources\Services\ServiceSelesaiResource\Pages;

use App\Filament\Resources\Services\ServiceSelesaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceSelesais extends ListRecords
{
    protected static string $resource = ServiceSelesaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->hidden(fn() => auth()->user()->roles->pluck('name')[0] === 'customer_service'),
        ];
    }
}
