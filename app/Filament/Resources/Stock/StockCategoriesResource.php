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
                Forms\Components\TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required(),
                Forms\Components\Select::make('jenis')
                    ->options([
                        'Stockin'    => 'STOCKIN',
                        'Stockout'   => 'STOCKOUT'
                    ])
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('jenis')
                    ->label('Jenis Stok')
                    ->colors([
                        'primary'   => "Stockin",
                        'danger'   => "Stockout",
                    ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hiddenLabel()->tooltip('Edit'),
                Tables\Actions\DeleteAction::make()->hiddenLabel()->tooltip('Delete'),
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
            // 'create' => Pages\CreateStockCategories::route('/create'),
            // 'edit' => Pages\EditStockCategories::route('/{record}/edit'),
        ];
    }
}
