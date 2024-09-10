<?php

namespace App\Filament\Resources\Services\ServiceDataResource\Pages;

use App\Filament\Resources\Services\ServiceDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceData extends ListRecords
{
    protected static string $resource = ServiceDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->hidden(fn() => auth()->user()->roles->pluck('name')[0] === 'Teknisi'),
        ];
    }
}
