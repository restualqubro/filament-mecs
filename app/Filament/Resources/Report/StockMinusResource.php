<?php

namespace App\Filament\Resources\Report;

use App\Filament\Resources\Report\StockMinusResource\Pages;
use App\Filament\Resources\Report\StockMinusResource\RelationManagers;
use App\Models\products\ProductCategories;
use App\Models\Products\Stock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockMinusResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Report';    

    protected static ?string $pluralModelLabel = 'Stock Minus';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fullCode'),
                Tables\Columns\TextColumn::make('product.name'),
                Tables\Columns\TextColumn::make('product.category.name'),
                Tables\Columns\TextColumn::make('stok'),
                Tables\Columns\TextColumn::make('hbeli')
                    ->numeric(decimalPlaces:0),
                Tables\Columns\TextColumn::make('product.hjual')
                    ->numeric(decimalPlaces:0)
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')                    
                    ->label('by Category')
                    ->options(
                        ProductCategories::all()->pluck('name', 'id')
                    )
                    ->modifyQueryUsing(function (Builder $query, $state)
                    {
                        if (! $state['value']) {
                            return $query;
                        }
                        // return $query->whereHas('businesses', fn($query) => $query->where('id', $state['value']));
                        return $query->whereHas('product', fn($query) => $query->where('category_id', $state['value']));
    
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(function (Builder $query) { 
                return $query->where('stok', '<=', 1);                
            }) ;
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
            'index' => Pages\ListStockMinuses::route('/'),
            'create' => Pages\CreateStockMinus::route('/create'),
            'edit' => Pages\EditStockMinus::route('/{record}/edit'),
        ];
    }
}
