<?php

namespace App\Filament\Clusters\BonusService\Resources;

use App\Filament\Clusters\BonusService;
use App\Filament\Clusters\BonusService\Resources\PiutangBonusResource\Pages;
use App\Filament\Clusters\BonusService\Resources\PiutangBonusResource\RelationManagers;
use App\Models\Finance\PiutangBonus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PiutangBonusResource extends Resource
{
    protected static ?string $model = PiutangBonus::class;

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
            'index' => Pages\ListPiutangBonuses::route('/'),
            'create' => Pages\CreatePiutangBonus::route('/create'),
            'edit' => Pages\EditPiutangBonus::route('/{record}/edit'),
        ];
    }
}
