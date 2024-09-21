<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Service\Invoice;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Support\Enums\MaxWidth;

class PiutangServiceTableWidget extends BaseWidget
{
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Invoice::query()->where('status', 'Piutang'))
            ->groups(['status'])
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Faktur'),
                Tables\Columns\TextColumn::make('selesai.service.code')
                    ->label('Kode Service'),
                Tables\Columns\TextColumn::make('selesai.service.customer.name')                
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Usia')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->diffInDays(\Carbon\Carbon::parse(now()))),                    
                Tables\Columns\TextColumn::make('sisa')
                    ->numeric(decimalPlaces:0)
                    ->label('Sisa Pembayaran')
            ])
            ->defaultSort('code', 'DESC');
    }
    
}
