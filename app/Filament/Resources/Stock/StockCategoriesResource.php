<?php

namespace App\Filament\Resources\Stock;

use App\Filament\Resources\Stock\StockCategoriesResource\Pages;
use App\Filament\Resources\Stock\StockCategoriesResource\RelationManagers;
use App\Models\Products\StockCategories;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockCategoriesResource extends Resource
{
    protected static ?string $model = StockCategories::class;

    protected static ?string $navigationGroup = 'Stock';

    protected static ?string $navigationIcon = 'heroicon-o-tag';

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
            'index' => Pages\ListStockCategories::route('/'),
            'create' => Pages\CreateStockCategories::route('/create'),
            'edit' => Pages\EditStockCategories::route('/{record}/edit'),
        ];
    }
}
