<?php

namespace App\Filament\Clusters\Pembelian\Resources\PembelianResource\Pages;

use App\Filament\Clusters\Pembelian\Resources\PembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembelian extends EditRecord
{
    protected static string $resource = PembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->icon('heroicon-o-arrow-left')
                ->color('primary')
                ->label('Back to Tables')
                ->url(fn (): string => static::getResource()::getUrl('index')),
        ];
    }
}
