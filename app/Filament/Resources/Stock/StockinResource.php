<?php

namespace App\Filament\Resources\Stock;

use App\Filament\Resources\Stock\StockinResource\Pages;
use App\Filament\Resources\Stock\StockinResource\RelationManagers;
use App\Models\Products\Stockin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockinResource extends Resource
{
    protected static ?string $model = Stockin::class;

    protected static ?string $navigationGroup = 'Stock';

    protected static ?string $pluralModelLabel = 'Stockin';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square';

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
            'index' => Pages\ListStockins::route('/'),
            'create' => Pages\CreateStockin::route('/create'),
            'edit' => Pages\EditStockin::route('/{record}/edit'),
        ];
    }
}
