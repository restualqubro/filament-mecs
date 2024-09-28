<?php

namespace App\Filament\Clusters\Keuangan\Resources;

use App\Filament\Clusters\Keuangan;
use App\Filament\Clusters\Keuangan\Resources\PemasukanResource\Pages;
use App\Filament\Clusters\Keuangan\Resources\PemasukanResource\RelationManagers;
use App\Models\Transaksi\Pemasukan;
use App\Models\Transaksi\KeuanganCategories;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PemasukanResource extends Resource
{
    protected static ?string $model = Pemasukan::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-left-start-on-rectangle';

    protected static ?string $pluralModelLabel = 'Pemasukan';

    protected static ?string $slug = 'transaksi-pemasukan';    

    protected static ?string $cluster = Keuangan::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->required()
                    ->options(KeuanganCategories::where('jenis', 'Pemasukan')->get()->pluck('name', 'id')),
                Forms\Components\TextInput::make('nominal')
                    ->label('Nominal pemasukan')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Description'),
                    Forms\Components\Hidden::make('submitted_id')
                    ->default(auth()->id())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nominal')
                    ->label('Nominal')
                    ->numeric(decimalPlaces:0),
                Tables\Columns\TextColumn::make('submitted.name')
                    ->label('Submitted By')                
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
            'index' => Pages\ListPemasukans::route('/'),
            // 'create' => Pages\CreatePemasukan::route('/create'),
            // 'edit' => Pages\EditPemasukan::route('/{record}/edit'),
        ];
    }
}
