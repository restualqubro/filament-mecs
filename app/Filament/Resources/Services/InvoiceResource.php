<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\InvoiceResource\Pages;
use App\Filament\Resources\Services\InvoiceResource\RelationManagers;
use App\Models\Service\Invoice;
use App\Models\Service\Selesai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Service';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('selesai_id')
                    ->label('Kode Service')
                    ->searchable()                
                    ->options(Selesai::all()->pluck('service.code', 'id'))
                    ->reactive()
                    ->afterStateUpdated(function($state, callable $set) {
                        $selesai = Selesai::find($state);                         
                        if ($selesai) {
                            $set('customer_name', $selesai->service->customer->name);                        
                        } else {
                            $set('customer_name', 'oraono');
                        }
                        // $set('customer_name', $selesai->service->customer->name);                        
                    }),
                Forms\Components\TextInput::make('customer_name')
                    ->label('Nama Customer')
                    ->disabled(),                                
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
