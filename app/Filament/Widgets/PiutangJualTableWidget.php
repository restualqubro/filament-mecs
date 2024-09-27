<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Transaksi\Jual;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Widgets\TableWidget as BaseWidget;

class PiutangJualTableWidget extends BaseWidget
{
    use InteractsWithTable;

    protected static ?string $heading = 'Omzet';

    protected int | string | array  $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Piutang Penjualan')
            ->query(Jual::query()->where('status', 'Piutang'))            
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Faktur'),                
                Tables\Columns\TextColumn::make('customer.name')                
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
