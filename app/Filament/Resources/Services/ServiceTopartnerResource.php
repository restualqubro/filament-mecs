<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\ServiceTopartnerResource\Pages;
use App\Filament\Resources\Services\ServiceTopartnerResource\RelationManagers;
use App\Models\Service\ToPartner;
use App\Models\Service\Data;
use App\Models\Connect\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceTopartnerResource extends Resource
{
    protected static ?string $model = Topartner::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Service';

    protected static ?string $pluralModelLabel = 'Service to Partner';

    protected static ?string $slug = 'service-topartner';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_id')
                    ->label('Kode Service')
                    ->searchable()
                    ->reactive()
                    ->options(Data::all()->where('status', 'Proses')->pluck('code', 'id')),
                Forms\Components\Select::make('partner_id')
                    ->label('Partner')
                    ->searchable()
                    ->reactive()
                    ->options(Partner::all()->pluck('name', 'id')),
                Forms\Components\DatePicker::make('date_send')                    
                    ->label('Tanggal Pengiriman')
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service.code')
                    ->label('Kode Service'),
                Tables\Columns\TextColumn::make('service.customer.name')
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('service.merk') 
                    ->label('Merk / Brand'),
                Tables\Columns\TextColumn::make('service.seri')
                    ->label('Seri / Tipe'),
                Tables\Columns\TextColumn::make('partner.name'),
                Tables\Columns\TextColumn::make('status')
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
            'index' => Pages\ListServiceTopartners::route('/'),
            // 'create' => Pages\CreateServiceTopartner::route('/create'),
            // 'edit' => Pages\EditServiceTopartner::route('/{record}/edit'),
        ];
    }
}
