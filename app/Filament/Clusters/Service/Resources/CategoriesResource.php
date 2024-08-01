<?php

namespace App\Filament\Clusters\Service\Resources;

use App\Filament\Clusters\Service;
use App\Filament\Clusters\Service\Resources\CategoriesResource\Pages;
use App\Filament\Clusters\Service\Resources\CategoriesResource\RelationManagers;
use App\Models\Service\Categories;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Pages\SubNavigationPosition;

class CategoriesResource extends Resource
{
    protected static ?string $model = Categories::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $slug = 'service-categories';

    protected static ?string $pluralModelLabel = 'Service Categories';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $cluster = Service::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required()                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable()
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
            'index' => Pages\ListCategories::route('/'),
            // 'create' => Pages\CreateCategories::route('/create'),
            // 'edit' => Pages\EditCategories::route('/{record}/edit'),
        ];
    }
}
