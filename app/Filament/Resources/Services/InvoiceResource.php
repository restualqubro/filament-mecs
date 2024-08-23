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

    protected static ?int $navigationSort = 6;

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
                    })
                    ->columnSpan(3),
                Forms\Components\TextInput::make('customer_name')
                    ->label('Nama Customer')
                    ->disabled()
                    ->columnSpan(3),    
                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('Generate')
                        ->action(function (Forms\Get $get, Forms\Set $set) { 
                            $selesai = Selesai::find($get('selesai_id'));                                                                                                                                  
                            if ($selesai) {
                                $set('subtotal_products', number_format($selesai->subtotal_products, 0, '', '.'));
                                $set('totaldiscount_products', number_format($selesai->totaldiscount_products, 0, '', '.'));
                                $set('subtotal_service', number_format($selesai->subtotal_service, 0, '', '.'));
                                $set('totaldiscount_service', number_format($selesai->totaldiscount_service, 0, '', '.'));                                
                                $set('total', number_format($selesai->total, 0, '', '.'));
                            } 
                            else {  
                                $set('subtotal_products', 'Generate Kode gagal!!');
                                $set('totaldiscount_products', 'Generate Kode gagal!!');
                                $set('subtotal_service', 'Generate Kode gagal!!');
                                $set('totaldiscount_service', 'Generate Kode gagal!!');                                
                                $set('total', 'Generate Kode gagal!!');              
                            }
                        }),
                    ])->columnSpan(6),    
                Forms\Components\TextInput::make('subtotal_products')
                    ->label('Subtotal Products')
                    ->disabled()
                    ->columnSpan(3),                    
                Forms\Components\TextInput::make('totaldiscount_products')
                    ->label('Total Discount Products')
                    ->disabled()
                    ->columnSpan(3),                           
                Forms\Components\TextInput::make('subtotal_service')
                    ->label('Subtotal Service')
                    ->disabled()
                    ->columnSpan(3),                                
                Forms\Components\TextInput::make('totaldiscount_service')
                    ->label('Total Discount Service')
                    ->disabled()
                    ->columnSpan(3),    
                Forms\Components\TextInput::make('total')
                    ->label('Total Invoice')
                    ->disabled()
                    ->columnSpan(2),
                Forms\Components\TextInput::make('totalbayar')
                    ->label('Total Pembayaran')
                    ->numeric()
                    ->required()                    
                    ->columnSpan(2)
                    ->reactive()
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                        self::updateSisaPembayaran($get, $set);
                    }),                                                                                     
                Forms\Components\TextInput::make('sisa')
                    ->label('Sisa Pembayaran')
                    ->numeric()
                    ->required()
                    ->columnSpan(2),        
                Forms\Components\Textarea::make('description')             
                    ->label('Keterangan')
                    ->columnSpan(6)
            ])
            ->columns(6);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('selesai.service.code')
                    ->label('Kode Faktur'),
                Tables\Columns\TextColumn::make('selesai.service.customer.name')
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Lunas' => 'warning',
                        'Cash' => 'success',
                        'Piutang' => 'danger',                        
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->hiddenLabel()->tooltip('Details'),
                Tables\Actions\EditAction::make()->hiddenLabel()->tooltip('Edit'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function updateSisaPembayaran(Forms\Get $get, Forms\Set $set): void
    {
        $total = (int)str_replace('.', '', $get('total'));                             

        $sisa = $total - $get('totalbayar');        
        $set('sisa', number_format($sisa, 0, '', '.'));        

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
            // 'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}

