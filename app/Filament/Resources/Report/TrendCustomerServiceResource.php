<?php

namespace App\Filament\Resources\Report;

use App\Filament\Resources\Report\TrendCustomerServiceResource\Pages;
use App\Models\Connect\Customers;
use App\Models\Service\Data;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TrendCustomerServiceResource extends Resource
{
    protected static ?string $model = Customers::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Report';

    protected static ?string $pluralModelLabel = 'Trend Service by Customer';    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('telp'),
                Tables\Columns\TextColumn::make('count')                
                    ->default(fn($record, Data $data) => $data->where('customer_id', $record->id)->count())
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrendCustomerServices::route('/'),
            'create' => Pages\CreateTrendCustomerService::route('/create'),
            'edit' => Pages\EditTrendCustomerService::route('/{record}/edit'),
        ];
    }
}
