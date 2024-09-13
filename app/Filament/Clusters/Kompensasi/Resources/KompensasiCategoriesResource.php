<?php

namespace App\Filament\Clusters\Kompensasi\Resources;

use App\Filament\Clusters\Kompensasi;
use App\Filament\Clusters\Kompensasi\Resources\KompensasiCategoriesResource\Pages;
use App\Filament\Clusters\Kompensasi\Resources\KompensasiCategoriesResource\RelationManagers;
use App\Models\Finance\KompensasiCategories;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KompensasiCategoriesResource extends Resource
{
    protected static ?string $model = KompensasiCategories::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster = Kompensasi::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Kategori')
                    ->requried()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
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
            'index' => Pages\ListKompensasiCategories::route('/'),
            // 'create' => Pages\CreateKompensasiCategories::route('/create'),
            // 'edit' => Pages\EditKompensasiCategories::route('/{record}/edit'),
        ];
    }
}
