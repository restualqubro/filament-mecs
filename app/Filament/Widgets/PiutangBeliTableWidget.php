<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Transaksi\Beli;
use Filament\Widgets\TableWidget as BaseWidget;

class PiutangBeliTableWidget extends BaseWidget
{    
    
    protected int | string | array  $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Beli::query()->where('status', 'Utang'))            
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Faktur'),                
                Tables\Columns\TextColumn::make('supplier.name')                
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
