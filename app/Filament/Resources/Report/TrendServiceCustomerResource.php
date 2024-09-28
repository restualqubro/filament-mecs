<?php

namespace App\Filament\Resources\Report;

use App\Filament\Resources\Report\TrendServiceCustomerResource\Pages;
use App\Filament\Resources\Report\TrendServiceCustomerResource\RelationManagers;
use App\Models\Service\Data;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrendServiceCustomerResource extends Resource
{
    protected static ?string $model = Data::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Report';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListTrendServiceCustomers::route('/'),
            'create' => Pages\CreateTrendServiceCustomer::route('/create'),
            'edit' => Pages\EditTrendServiceCustomer::route('/{record}/edit'),
        ];
    }
}
