<?php

namespace App\Filament\Clusters\BonusService\Resources;

use App\Filament\Clusters\BonusService;
use App\Filament\Clusters\BonusService\Resources\UtangBonusResource\Pages;
use App\Filament\Clusters\BonusService\Resources\UtangBonusResource\RelationManagers;
use App\Models\Finance\UtangBonus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UtangBonusResource extends Resource
{
    protected static ?string $model = UtangBonus::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = BonusService::class;

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
            'index' => Pages\ListUtangBonuses::route('/'),
            'create' => Pages\CreateUtangBonus::route('/create'),
            'edit' => Pages\EditUtangBonus::route('/{record}/edit'),
        ];
    }
}
