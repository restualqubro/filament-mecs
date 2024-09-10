<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\InvoiceResource\Pages;
use App\Models\Service\Invoice;
use App\Models\Service\Selesai;
use App\Models\Service\PiutangService;
use App\Models\Service\LogService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\MaxWidth;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Carbon\Carbon;
use Filament\Support\Enums\FontWeight;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Service';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        $selesai = Selesai::all()->where('service.status', 'Selesai');
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Faktur Penjualan')
                            ->default(function() {
                                $date = Carbon::now()->format('my');
                                $last = Invoice::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
                                if ($last != null) {                                                                                            
                                    $tmp = substr($last, 8, 4)+1;
                                    return "FKS-".$date.sprintf("%03s", $tmp);                                                                            
                                } else {
                                    return "FKS-".$date."001";
                                }
                            })
                            ->readonly()
                            ->required()
                            ->columnSpan(2),   
                        Forms\Components\Select::make('selesai_id')
                            ->label('Kode Service')
                            ->searchable()                
                            ->options($selesai->mapWithKeys(function (Selesai $selesai) {
                                return [$selesai->id => sprintf($selesai->service->code)];
                            }))
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
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('customer_name')
                            ->label('Nama Customer')
                            ->disabled()
                            ->columnSpan(2),    
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
                            ->default(0)               
                            ->columnSpan(2)
                            ->reactive()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                self::updateSisaPembayaran($get, $set);
                            }),                                                                                     
                        Forms\Components\TextInput::make('sisa')
                            ->label('Sisa Pembayaran')                            
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->columnSpan(2),        
                        Forms\Components\Textarea::make('description')             
                            ->label('Keterangan')
                            ->columnSpan(6)
                    ])->columns(6),
            ]);             
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Faktur'),
                Tables\Columns\TextColumn::make('selesai.service.customer.name')
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('selesai.service.merk')
                    ->label('Merk'),
                Tables\Columns\TextColumn::make('selesai.service.seri')
                    ->label('Seri/Tipe'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date(),
                Tables\Columns\TextColumn::make('sisa')
                    ->label('Sisa')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Lunas' => 'warning',
                        'Cash' => 'success',
                        'Piutang' => 'danger',                        
                    }),                
            ])
            ->filters([
                //
            ])
            ->actions([                
                Tables\Actions\ViewAction::make()->hiddenLabel()->tooltip('Details'),
                Tables\Actions\Action::make('pelunasan')->hiddenLabel()->tooltip('Pelunasan')
                    ->label('Pelunasan')
                    ->color('warning')
                    ->icon('heroicon-o-queue-list')                    
                    ->form([                                  
                        Forms\Components\TextInput::make('code')
                            ->label('Faktur Penjualan')
                            ->disabled()
                            ->dehydrated()
                            ->default(fn(Invoice $record): string => $record->code),
                        Forms\Components\TextInput::make('sisa')
                            ->label('Sisa Pembayaran')
                            ->disabled()                        
                            ->default(fn(Invoice $record): string => number_format($record->sisa, '0', '', '.')),                        
                        // Forms\Components\Hidden::make('tot_bayar')
                        //     ->default(fn(Jual $record) => $record->tot_bayar),
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Pelunasan')
                            ->default(now())
                            ->required(),
                        Forms\Components\TextInput::make('bayar')
                            ->label('Nominal Pembayaran')                            
                            ->required(),
                    ])
                    ->action(function (array $data, Invoice $invoice): void {                        
                        $record[] = array();
                        $record['user_id'] = auth()->user()->id;
                        $record['invoice_id'] = $invoice->id;                        
                        $record['bayar']   = $data['bayar'];                        
                        $sisa = $invoice->sisa - $data['bayar'];
                        $totalbayar = $invoice->totalbayar + $data['bayar'];
                        if ($sisa > 0) {
                            $status = 'Piutang';
                        } else {
                            $status = 'Lunas';
                        }
                        PiutangService::Create($record);
                        Invoice::where('id', $invoice->id)->update([
                            'sisa'      => $sisa,
                            'status'    => $status,
                            'totalbayar' => $totalbayar,
                        ]);                        
                    })
                    ->visible(fn (Invoice $record): bool => $record->status === 'Piutang')
                    ->modalWidth(MaxWidth::Medium),
                Tables\Actions\EditAction::make()->hiddenLabel()->tooltip('Edit'),
                Tables\Actions\DeleteAction::make()->hiddenLabel()->tooltip('Delete')
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([     
                Section::make('Service Details')
                    ->schema([
                        TextEntry::make('code')
                            ->label('Kode Service')
                            ->weight(FontWeight::Bold), 
                        TextEntry::make('updated_at')
                            ->label('Last Updated'), 
                        TextEntry::make('selesai.service.customer.name')
                            ->label('Nama Customer'), 
                        TextEntry::make('selesai.service.merk')
                            ->label('Merk/Brand'),
                        TextEntry::make('selesai.service.seri')
                            ->label('Seti/Tipe'),                        
                        TextEntry::make('selesai.service.keluhan')
                            ->label('Keluhan'), 
                        TextEntry::make('selesai.subtotal_service')
                            ->label('Subtotal Service')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),                                                                                                 
                        TextEntry::make('selesai.totaldiscount_service')
                            ->label('Total Discount')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),    
                        TextEntry::make('selesai.total')
                            ->label('Total')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),                         
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->colors([
                                'success'   => 'Cash',
                                'warning'   => 'Lunas',
                                'danger'    => 'Piutang'
                            ]), 
                        TextEntry::make('totalbayar')
                            ->label('Total Dibayarkan')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),        
                        TextEntry::make('sisa') 
                            ->label('Sisa Pembayaran')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),        
                ])->columns(2),                                                             
                RepeatableEntry::make('detailPiutang')
                    ->label('Riwayat Pelunasan Piutang')
                    ->schema([                                                                                                                                                                                                  
                        TextEntry::make('created_at'),
                        TextEntry::make('bayar')                            
                            ->label('Nomimal')
                            ->money('IDR')
                    ])      
                    ->columns(2) 
                    ->columnSpan('full')                                 
                    ->grid(2),                   
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'view' => Pages\ViewDetails::route('/{record}/view'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}

