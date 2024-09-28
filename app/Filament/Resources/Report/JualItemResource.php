<?php

namespace App\Filament\Resources\Report;

use App\Filament\Resources\Report\JualItemResource\Pages;
use App\Filament\Resources\Report\JualItemResource\RelationManagers;
use App\Models\Transaksi\DetailJual;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JualItemResource extends Resource
{
    protected static ?string $model = DetailJual::class;

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
            'index' => Pages\ListJualItems::route('/'),
            'create' => Pages\CreateJualItem::route('/create'),
            'edit' => Pages\EditJualItem::route('/{record}/edit'),
        ];
    }
}
