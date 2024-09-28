<?php

namespace App\Filament\Clusters\Kompensasi\Resources;

use App\Filament\Clusters\Kompensasi;
use App\Filament\Clusters\Kompensasi\Resources\KompensasiDataResource\Pages;
use App\Filament\Clusters\Kompensasi\Resources\KompensasiDataResource\RelationManagers;
use App\Models\Finance\Kompensasi as KompensasiData;
use App\Models\Finance\KompensasiCategories;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KompensasiDataResource extends Resource
{
    protected static ?string $model = KompensasiData::class;

    protected static ?string $navigationIcon = 'heroicon-o-scissors';

    protected static ?string $pluralModelLabel = 'Kompensasi';

    protected static ?string $cluster = Kompensasi::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nominal')
                    ->label('Nominal Kompensasi')
                    ->required(),
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->options(KompensasiCategories::all()->pluck('name', 'id')),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columns(2)
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nominal'),
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])                
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
            'index' => Pages\ListKompensasiData::route('/'),
            // 'create' => Pages\CreateKompensasiData::route('/create'),
            // 'edit' => Pages\EditKompensasiData::route('/{record}/edit'),
        ];
    }
}
