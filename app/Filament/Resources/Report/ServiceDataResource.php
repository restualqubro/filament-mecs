<?php

namespace App\Filament\Resources\Report;

use App\Filament\Resources\Report\ServiceDataResource\Pages;
use App\Filament\Resources\Report\ServiceDataResource\RelationManagers;
use App\Models\Service\Data;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceDataResource extends Resource
{
    protected static ?string $model = Data::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Report';

    protected static ?string $pluralModelLabel = 'Service Data';
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('KODE SERVICE'),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_in')
                    ->label('Tanggal Masuk')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('merk')
                    ->label('Brand/Merk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seri')
                    ->label('Seri/Tipe')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Baru' => 'gray',
                        'Proses' => 'warning',
                        'Selesai' => 'success',
                        'Cancel' => 'danger',
                        'Keluar' => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('by Status')
                    ->options([
                        'Baru'      => 'Baru',
                        'Proses'    => 'Proses',
                        'Selesai'   => 'Selesai',
                        'Cancel'    => 'Cancel',
                        'Keluar'    => 'Keluar'
                    ])
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
            'index' => Pages\ListServiceData::route('/'),
            'create' => Pages\CreateServiceData::route('/create'),
            'edit' => Pages\EditServiceData::route('/{record}/edit'),
        ];
    }
}
