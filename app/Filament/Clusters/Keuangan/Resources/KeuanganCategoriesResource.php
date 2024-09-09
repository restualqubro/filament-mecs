<?php

namespace App\Filament\Clusters\Keuangan\Resources;

use App\Filament\Clusters\Keuangan;
use App\Filament\Clusters\Keuangan\Resources\KeuanganCategoriesResource\Pages;
use App\Filament\Clusters\Keuangan\Resources\KeuanganCategoriesResource\RelationManagers;
use App\Models\Transaksi\KeuanganCategories;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KeuanganCategoriesResource extends Resource
{
    protected static ?string $model = KeuanganCategories::class;

    protected static ?string $pluralModelLabel = 'Categories';

    protected static ?string $slug = 'keuangan-categories';

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster = Keuangan::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required(),
                Forms\Components\Select::make('jenis')
                    ->label('Jenis Kategori')
                    ->options([
                        'Pemasukan' => 'Pemasukan',
                        'Pengeluaran'   => 'Pengeluaran'
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis Kategori')
                    ->badge()
                    ->colors([
                        'warning' => 'Pengeluaran',
                        'success'   => 'Pemasukan'
                    ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListKeuanganCategories::route('/'),
            // 'create' => Pages\CreateKeuanganCategories::route('/create'),
            // 'edit' => Pages\EditKeuanganCategories::route('/{record}/edit'),
        ];
    }
}
