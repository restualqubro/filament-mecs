<?php

namespace App\Filament\Resources\Retur;

use App\Filament\Resources\Retur\ReturPenjualanResource\Pages;
use App\Filament\Resources\Retur\ReturPenjualanResource\RelationManagers;
use App\Models\Retur\ReturJual;
use App\Models\Products\Stock;
use App\Models\Transaksi\Jual;
use App\Models\Transaksi\DetailJual;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class ReturPenjualanResource extends Resource
{
    protected static ?string $model = ReturJual::class;

    protected static ?string $slug = 'sale';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Retur';
    
    protected static ?string $pluralModelLabel = 'Retur Penjualan';    
    

    public static function form(Form $form): Form
    {        
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->label('Faktur Penjualan')
                                    ->default(function() {
                                        $date = Carbon::now()->format('my');
                                        $last = ReturJual::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
                                        if ($last != null) {                                                                                            
                                            $tmp = substr($last, 8, 4)+1;
                                            return "RTJ-".$date.sprintf("%03s", $tmp);                                                                            
                                        } else {
                                            return "RTJ-".$date."001";
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
                                Forms\Components\Select::make('jual_id')                                                                                                                                                
                                    ->label('Kode Faktur Jual')
                                    ->required()
                                    ->options(Jual::all()->pluck('code', 'id'))
                                    ->searchable()
                                    ->columnSpan([
                                        'md' => 2
                                    ])
                            ])->columns(6),                                
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\TextArea::make('description')
                                            ->rows(1)                                                                    
                                ])->columns('full'),  
                        ]),                                                                                 
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Products'),
                                Forms\Components\Repeater::make('detailRetur')
                                    ->label('Detail Items')                                                                    
                                    ->relationship()
                                    ->collapsible()
                                    ->schema([                                        
                                        Forms\Components\Select::make('stock_id')
                                            ->label('Kode Stock')                                                                                        
                                            ->options(function (Forms\Get $get) {
                                                $detail = DetailJual::where('jual_id', $get('jual_id'))->has('stock')->get();
                                                // return dd($detail);
                                            })                                                                                            
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
                                                    $set('hjual', $stock->product->hjual);
                                                    $set('hbeli', $stock->hbeli);
                                                }
                                            })                                           
                                            ->columnSpan([
                                                'md' => 5
                                            ]),                                  
                                        Forms\Components\Hidden::make('hbeli'),                                        
                                        Forms\Components\TextInput::make('hjual')                                            
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
                                                    $hbeli = $get('hbeli');
                                                    $jumlah = $qty * ($hjual - $disc);
                                                    $profit = (($hjual - $disc) - $hbeli) * $qty;
                                                    $set('profit', $profit);
                                                    $set('jumlah', number_format($jumlah, 0, '', '.'));
                                                }
                                            ), 
                                        Forms\Components\Hidden::make('profit'),                                       
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
                                        fn(Forms\Components\Actions\Action $action) => $action->after(fn(Forms\Get $get, Forms\Set $set) => self::updateTotalHarga($get, $set)),
                                    )
                                    ->defaultItems(1)
                                    ->columns([
                                        'md' => 10
                                    ])
                                    ->columnSpan('full')
                            ]),
                        Forms\Components\Card::make()                                                                                              
                            ->schema([                                  
                            Forms\Components\TextInput::make('subtotal')
                                ->label('Subtotal')                                    
                                ->disabled()
                                ->dehydrated()
                                ->required(),                                                                                                           
                            Forms\Components\TextInput::make('totaldiscount')
                                ->label('Total Discount')                                    
                                ->disabled()
                                ->dehydrated()
                                ->required(),                                                           
                            Forms\Components\TextInput::make('totalharga')
                                ->label('Total')                                    
                                ->disabled()
                                ->dehydrated()
                                ->required()
                            ])                                                                                           
                    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Faktur Penjualan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date(),                
                Tables\Columns\TextColumn::make('customer.name')        
                    ->label('Supplier'),
                Tables\Columns\TextColumn::make('tot_har')                    
                    ->label('Total Harga')
                    ->money('IDR'),
                Tables\Columns\BadgeColumn::make('status')
                    ->color(fn (string $state): string => match ($state) {                        
                        'Lunas' => 'warning',
                        'Cash' => 'success',
                        'Piutang' => 'danger',
                    })
                    ->label('Status Pembayaran')            
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->hiddenLabel()->tooltip('Detail'),  
                Tables\Actions\Action::make('print')
                    ->hiddenLabel()
                    ->tooltip('Print')
                    ->url(fn ($record) => '/print/fakturjual/'.$record->id)
                    ->color('warning')
                    ->icon('heroicon-o-printer')                    
                    ->openUrlInNewTab(),                        
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
        $tot_dp = 0;
        $subtotal = 0;
        $totaldiscount = 0;
        $total = 0;        
        foreach($selectedProducts as $item) {
            $subtotal += $item['hjual'] * $item['qty'];
            $totaldiscount += $item['disc'] * $item['qty'];            
        }                      
        $tot_dp = $get('tot_dp');        
        $total = $subtotal - $totaldiscount - $tot_dp;
        // Update the state with the new values
        $set('subtotal', number_format($subtotal, 0, '', '.'));
        $set('tot_disc', $totaldiscount);
        $set('out_tot_disc', number_format($totaldiscount, 0, '', '.'));
        $set('tot_har', $total);
        $set('out_tot_har', number_format($total, 0, '', '.'));                
        

    }

    public static function updateSisaPembayaran(Forms\Get $get, Forms\Set $set): void
    {            
        if (!empty($get('tot_har'))) {            
            $sisa = $get('tot_har') - $get('tot_bayar');                     
            if ($sisa > 0) {                
                $status = 'Piutang';        
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReturPenjualans::route('/'),
            'create' => Pages\CreateReturPenjualan::route('/create'),
            'edit' => Pages\EditReturPenjualan::route('/{record}/edit'),
        ];
    }
}
