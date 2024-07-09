<?php

namespace App\Filament\Clusters\Penjualan\Resources;

use App\Filament\Clusters\Penjualan;
use App\Filament\Clusters\Penjualan\Resources\PenjualanResource\Pages;
use App\Filament\Clusters\Penjualan\Resources\PenjualanResource\RelationManagers;
use App\Models\Transaksi\Jual;
use App\Models\Products\Stock;
use App\Models\Connect\Customers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\Repeater;
use Filament\Pages\SubNavigationPosition;
use Filament\Support\Enums\MaxWidth;

class PenjualanResource extends Resource
{
    protected static ?string $model = Jual::class;    

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;    

    protected static ?string $cluster = Penjualan::class;

    public static function form(Form $form): Form
    {
        $stock = Stock::get();
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Grid::make()                        
                            ->schema([                            
                                Forms\Components\Card::make()
                                    ->schema([
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('code')
                                                    ->label('Faktur Pembelian')
                                                    ->default(function() {
                                                        $date = Carbon::now()->format('my');
                                                        $last = Jual::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
                                                        if ($last != null) {                                                                                            
                                                            $tmp = substr($last, 8, 4)+1;
                                                            return "FKJ-".$date.sprintf("%03s", $tmp);                                                                            
                                                        } else {
                                                            return "FKJ-".$date."001";
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
                                                Forms\Components\Select::make('customer_id')
                                                    ->label('Customer')
                                                    ->required()
                                                    ->options(Customers::all()->pluck('name','id'))
                                                    ->columnSpan([
                                                        'md' => 2
                                                    ]), 
                                                Forms\Components\Toggle::make('is_pending')
                                                    ->label('is pending ?')                                                    
                                                    ->onColor('success')
                                                    ->offColor('gray')   
                                                    ->columnSpan(6)                                   
                                            ])->columns(6),                                
                                        Forms\Components\Group::make()
                                        ->schema([
                                            Forms\Components\TextArea::make('description')
                                                ->rows(1)                                                                    
                                        ])
                                        ->columns('full')
                                    ])->columnSpan(6),
                                Forms\Components\Card::make()                                    
                                    ->schema([                                          
                                        Forms\Components\TextInput::make('preorder_id')
                                            ->label('Kode Preorder')                                            
                                            ->columnSpan([
                                                'md' => 2
                                            ]), 
                                        Forms\Components\TextInput::make('nominal_dp')
                                            ->label('Nominal DP')                                            
                                            ->disabled()                                                                                        
                                            ->columnSpan([
                                                'md' => 2
                                            ]),                                        
                                    ])->columnSpan(2),
                            ])
                            ->columns(8),                        
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Products'),
                                Repeater::make('detailJual')
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
                                            ->afterStateUpdated(function($state, callable $set) {
                                                $stock = Stock::find($state);
                                                if ($stock) {                                                    
                                                    $set('out_hjual', number_format($stock->product->hjual, 0, '', '.'));              
                                                    $set('hjual', $stock->product->hjual);
                                                }
                                            })                                           
                                            ->columnSpan([
                                                'md' => 5
                                            ]),                                                                                         
                                        Forms\Components\Hidden::make('hjual'),
                                        Forms\Components\TextInput::make('out_hjual')                                            
                                            ->label('Harga')
                                            ->disabled()                                            
                                            ->columnSpan([
                                                'md' => 1
                                            ]),                                        
                                        Forms\Components\TextInput::make('disc')                                            
                                            ->label('Discount')
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
                                                    $disc = $get('disc');
                                                    $qty = $get('qty');
                                                    $hjual = $get('hjual');
                                                    $jumlah = $qty * ($hjual - $disc);
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
                            Forms\Components\TextInput::make('dp')
                                ->label('Uang Muka / DP')                                    
                                ->disabled()
                                ->dehydrated()
                                ->required(), 
                            Forms\Components\TextInput::make('subtotal')
                                ->label('Subtotal')                                    
                                ->disabled()
                                ->dehydrated()
                                ->required(), 
                            Forms\Components\Hidden::make('tot_disc'),
                            Forms\Components\TextInput::make('out_tot_disc')
                                ->label('Total Discount')                                    
                                ->disabled()
                                ->dehydrated()
                                ->required(),                               
                            Forms\Components\Hidden::make('tot_har'),
                            Forms\Components\TextInput::make('total')
                                ->label('Total')                                    
                                ->disabled()
                                ->dehydrated()
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
                            Forms\Components\Hidden::make('status')                                
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
                Tables\Columns\TextColumn::make('supplier.name')        
                    ->label('Supplier'),
                Tables\Columns\TextColumn::make('tot_har')                    
                    ->label('Total Harga'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status Pembayaran')            
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
        $selectedProducts = collect($get('detailJual'))->filter(fn($item) => !empty($item['qty']) && !empty($item['hjual']) && !empty($item['disc']));        
        $total = 0;        
        foreach($selectedProducts as $item) {
            $subtotal = ($item['hjual'] - $item['disc']) * $item['qty'];            
            $total+= $subtotal;
        }              

        // Update the state with the new values
        $set('tot_har', $total);
        $set('out_har', number_format($total, 0, '', '.'));                
        

    }

    public static function updateSisaPembayaran(Forms\Get $get, Forms\Set $set): void
    {            
        if (!empty($get('tot_har'))) {            
            $sisa = $get('tot_har') - $get('tot_bayar');                     
            if ($sisa > 0) {                
                $status = 'Utang';        
                $set('status', $status);
            } else {                
                $status = 'Cash';
                $set('status', $status);
            }
        } else {
            $sisa = null;
        }        
        $set('out_sisa', number_format($sisa, 0, '', '.'));        
        // $set('out_sisa', $sisa);
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
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}
