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

    protected static ?string $heading = 'Omzet';

    protected int | string | array  $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Piutang Service')
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
            ->actions([
                Tables\Actions\Action::make('contact')
                        ->label('Contact')
                        ->url(function(Invoice $record) {                            
                            return 'https://wa.me/+62'.$record->selesai->service->customer->telp."?
                            text=Assalamu'alaikum";
                        })
                        ->color('success')
                        ->icon('heroicon-o-chat-bubble-bottom-center-text')
                        ->openUrlInNewTab()
                        ->hidden(fn() => auth()->user()->roles->pluck('name')[0] === 'teknisi'),
            ])
            ->defaultSort('code', 'DESC');
    }
    
}
