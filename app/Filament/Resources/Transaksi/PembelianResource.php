<?php

namespace App\Filament\Resources\Transaksi;

use App\Filament\Resources\Transaksi\PembelianResource\Pages;
use Filament\Forms\Components\Repeater;
use App\Models\Transaksi\Beli as Pembelian;
use App\Models\Products\Products;
use App\Models\Products\Stock;
use App\Models\Connect\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $pluralModelLabel = 'Pembelian';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        $stock = Stock::get();
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('code')
                                            ->label('Faktur Pembelian')
                                            ->default(function() {
                                                $date = Carbon::now()->format('my');
                                                $last = Pembelian::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
                                                if ($last != null) {                                                                                            
                                                    $tmp = substr($last, 8, 4)+1;
                                                    return "FKB-".$date.sprintf("%03s", $tmp);                                                                            
                                                } else {
                                                    return "FKB-".$date."001";
                                                }
                                            })
                                            ->readonly()
                                            ->required()
                                            ->columnSpan([
                                                'md' => 2
                                            ]),                                
                                        Forms\Components\DatePicker::make('tanggal')
                                            ->default(now())
                                            ->required()
                                            ->columnSpan([
                                                'md' => 2
                                            ]),                                                               
                                        Forms\Components\Select::make('supplier_id')
                                            ->label('Supplier')
                                            ->required()
                                            ->options(Supplier::all()->pluck('name','id'))
                                            ->columnSpan([
                                                'md' => 2
                                            ]), 
                                    ])->columns(6),                                
                                Forms\Components\Group::make()
                                ->schema([
                                    Forms\Components\TextArea::make('description'),                                
                                    Forms\Components\SpatieMediaLibraryFileUpload::make('media')
                                        ->label('Dokumentasi Nota/Invoice')
                                        ->collection('beli'),
                                ])
                                ->columns(2)
                            ]),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Products'),
                                Repeater::make('detailBeli')
                                    ->label('Detail Items')                                                                    
                                    ->relationship()
                                    ->collapsible()
                                    ->schema([                                        
                                        Forms\Components\Select::make('stock_id')
                                            ->label('Kode Stock')                                                                                        
                                            ->options(                                                
                                                $stock->mapWithKeys(function (Stock $stock) {
                                                    return [$stock->id => sprintf('%s-%s | %s', $stock->product->code, $stock->code, $stock->product->name)];
                                                })
                                                )                                                                                            
                                            ->required()
                                            ->searchable()
                                            ->reactive()
                                            ->disableOptionWhen(function ($value, $state, Forms\Get $get) {
                                                return collect($get('../*.stock_id'))
                                                    ->reject(fn($id) => $id == $state)
                                                    ->filter()
                                                    ->contains($value);
                                            })                                            
                                            ->columnSpan([
                                                'md' => 5
                                            ]),                                                 
                                        Forms\Components\TextInput::make('supplier_warranty')                                            
                                            ->label('Garansi')
                                            ->numeric()    
                                            ->required()                                        
                                            ->columnSpan([
                                                'md' => 1
                                            ]),
                                        Forms\Components\TextInput::make('hbeli')                                            
                                            ->label('Harga Beli')
                                            ->numeric()    
                                            ->required()                                        
                                            ->columnSpan([
                                                'md' => 1
                                            ]),                                        
                                        Forms\Components\TextInput::make('qty') 
                                            ->label('Qty')   
                                            ->numeric()    
                                            ->required()                                                                                                                                                                                                                            
                                            ->columnSpan([
                                                'md' => 1
                                            ])
                                            ->live()
                                            ->afterStateUpdated(
                                                function (Forms\Get $get, Forms\Set $set) {
                                                    $qty = $get('qty');
                                                    $hbeli = $get('hbeli');
                                                    $jumlah = $qty * $hbeli;
                                                    $set('jumlah', number_format($jumlah, 0, '', '.'));
                                                }
                                            ),                                        
                                        Forms\Components\TextInput::make('jumlah') 
                                            ->label('Jumlah')                                           
                                            ->disabled()                                                                                    
                                            ->columnSpan([
                                                'md' => 2
                                            ]),
                                    ])
                                    ->live()
                                    // After adding a new row, we need to update the totals
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                        self::updateTotalHarga($get, $set);
                                    })
                                    // After deleting a row, we need to update the totals
                                    ->deleteAction(
                                        fn(Forms\Components\Actions\Action $action) => $action->after(fn(Forms\Get $get, Forms\Set $set) => self::updateTotals($get, $set)),
                                    )
                                    ->defaultItems(1)
                                    ->columns([
                                        'md' => 10
                                    ])
                                    ->columnSpan('full')
                            ]),
                        Forms\Components\Card::make()                                                                                              
                            ->schema([
                            Forms\Components\Hidden::make('tot_har'),
                            Forms\Components\TextInput::make('out_har')
                                ->label('Subtotal')                                    
                                ->disabled()
                                ->dehydrated()
                                ->required(),
                            Forms\Components\TextInput::make('ongkir')
                                ->label('Ongkos Kirim')
                                ->numeric()
                                ->minValue(0)
                                ->required()
                                ->live()
                                ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                    $subtotal = $get('tot_har');
                                    $ongkir = $get('ongkir');
                                    $total = $subtotal + $ongkir;        
                                    $set('total', number_format($total, 0, '', '.'));
                                }),                            
                            Forms\Components\TextInput::make('total')
                                ->label('Total')
                                ->disabled()                                 
                                ->required(),
                            Forms\Components\TextInput::make('tot_bayar')
                                ->label('Total di Bayarkan')
                                ->numeric()
                                ->minValue(0)
                                ->live()
                                ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                    self::updateSisaPembayaran($get, $set);
                                })
                                ->required(),
                            Forms\Components\Hidden::make('sisa'),                                                                                  
                            Forms\Components\TextInput::make('out_sisa')
                                ->label('Sisa Pembayaran')                                
                                ->disabled()
                                ->dehydrated(),
                            Forms\Components\TextInput::make('status')
                                ->label('Status Pembayaran')
                                ->disabled()
                                ->dehydrated(),
                            ])->columns(3),
                    ])->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Faktur Pembelian')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date(),                
                Tables\Columns\TextColumn::make('ongkir')        
                    ->label('Ongkos Kirim')                    
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->hiddenLabel()->tooltip('Detail'),
                Tables\Actions\EditAction::make()->hiddenLabel()->tooltip('Edit'),
                Tables\Actions\DeleteAction::make()->hiddenLabel()->tooltip('Delete')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function updateTotalHarga(Forms\Get $get, Forms\Set $set): void
    {
        // Retrieve all selected products and remove empty rows
        $selectedProducts = collect($get('detailBeli'))->filter(fn($item) => !empty($item['qty']) && !empty($item['hbeli']) && !empty($item['jumlah']));        
        $total = 0;
        $totalqty = 0;
        foreach($selectedProducts as $item) {
            $subtotal = $item['hbeli'] * $item['qty'];
            $totalqty += $item['qty'];
            $total+= $subtotal;
        }              

        // Update the state with the new values
        $set('tot_har', $total);
        $set('out_har', number_format($total, 0, '', '.'));                
        

    }

    public static function updateSisaPembayaran(Forms\Get $get, Forms\Set $set): void
    {            
        if (!empty($get('tot_har')) && !empty($get('ongkir')) && !empty($get('tot_bayar'))) {
            $sisa = ($get('tot_har') + $get('ongkir')) - $get('tot_bayar');                        
            if ($sisa > 0) {
                $status = 'Utang';        
            } else {
                $status = 'Cash';
            }
        } else {
            $sisa = null;
        }        
        $set('out_sisa', number_format($sisa, 0, '', '.'));
        $set('status', $status);
        $set('sisa', $sisa);         
    }

    public function updateOngkir(Forms\Get $get, Forms\Set $set): void
    {        
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
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
        ];
    }
}
