<?php

namespace App\Filament\Clusters\Products\Resources;

use App\Filament\Clusters\Products;
use App\Filament\Clusters\Products\Resources\ProductBrandsResource\Pages;
use App\Filament\Clusters\Products\Resources\ProductBrandsResource\RelationManagers;
use App\Models\Products\ProductBrands;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Pages\SubNavigationPosition;

class ProductBrandsResource extends Resource
{
    protected static ?string $model = ProductBrands::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    

    protected static ?string $cluster = Products::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Brand')
                    ->required(),
                Forms\Components\TextInput::make('init')
                    ->label('Inisiasi Brand')
                    ->required()
                    ->placeholder('ex : ACER = ACR, ...')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Brand')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('init')
                    ->label('Inisiasi Brand')
            ])->defaultSort('name', 'ASC')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hiddenLabel()->tooltip('Edit'),
                Tables\Actions\DeleteAction::make()->hiddenLabel()->tooltip('Delete')
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
            'index' => Pages\ListProductBrands::route('/'),
            // 'create' => Pages\CreateProductBrands::route('/create'),
            // 'edit' => Pages\EditProductBrands::route('/{record}/edit'),
        ];
    }
}
