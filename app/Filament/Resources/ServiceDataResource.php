<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceDataResource\Pages;
use App\Filament\Resources\ServiceDataResource\RelationManagers;
use App\Models\Service\Data;
use App\Models\Connect\Customers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceDataResource extends Resource
{
    protected static ?string $model = Data::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Service';

    protected static ?string $slug = 'service-data';

    protected static ?string $pluralModelLabel = 'Service Data';
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date_in')
                    ->label('Tanggal Masuk')
                    ->required()
                    ->default('now'),
                Forms\Components\Select::make('customer_id')
                    ->label('Customer')
                    ->required()
                    ->options(Customers::all()->pluck('name', 'customer_id'))
                    ->searchable()
                    ->reactive()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListServiceData::route('/'),
            'create' => Pages\CreateServiceData::route('/create'),
            'edit' => Pages\EditServiceData::route('/{record}/edit'),
        ];
    }
}
