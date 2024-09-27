<?php

namespace App\Filament\Clusters\Penjualan\Resources;

use App\Filament\Clusters\Penjualan;
use App\Filament\Clusters\Penjualan\Resources\PenjualanResource\Pages;
use App\Models\Transaksi\PiutangPenjualan;
use App\Models\Transaksi\Jual;
use App\Models\Transaksi\DetailJual;
use App\Models\Products\Stock;
use App\Models\Connect\Customers;
use App\Models\Transaksi\Preorder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\Repeater;
use Filament\Pages\SubNavigationPosition;
use Filament\Support\Enums\MaxWidth;

class PenjualanResource extends Resource
{
    protected static ?string $model = Jual::class;    

    protected static ?string $slug = 'sale';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    
    protected static ?string $pluralModelLabel = 'Penjualan';

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
                                                    ->label('Faktur Penjualan')
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
                                            Forms\Components\Textarea::make('description')
                                                ->rows(1)                                                                    
                                        ])
                                        ->columns('full')
                                    ])->columnSpan(6),
                                Forms\Components\Card::make()                                    
                                    ->schema([                                          
                                        Forms\Components\Select::make('preorder_id')
                                            ->label('Kode Preorder')        
                                            ->reactive()
                                            ->searchable()                                       
                                            ->options(Preorder::where('status', 'Baru')->pluck('code','id'))                                                                                     
                                            ->columnSpan([
                                                'md' => 2
                                            ])
                                            ->afterStateUpdated(function($state, Forms\Set $set) {
                                                $preorder = Preorder::find($state);
                                                if ($preorder) {                                                    
                                                    $set('nominal_dp', number_format($preorder->nominal, 0, '', '.'));    
                                                    $set('totaldp', number_format($preorder->nominal, 0, '', '.'));                                                                                                    
                                                }
                                                }
                                            ), 
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
                                            ->dehydrated()                                           
                                            ->columnSpan([
                                                'md' => 1
                                            ]),                                        
                                        Forms\Components\TextInput::make('disc')                                            
                                            ->label('Discount')
                                            ->numeric()    
                                            ->required() 
                                            ->default(0)                                       
                                            ->columnSpan([
                                                'md' => 1
                                            ]),
                                        Forms\Components\TextInput::make('qty') 
                                            ->label('Qty')   
                                            ->numeric()    
                                            ->required()
                                            ->minValue(1)
                                            ->maxValue(function (Stock $item, Forms\Get $get, $state, $record): int 
                                                {                                                
                                                    if ($record) {    
                                                        $items = $item->find($record->stock_id);                                                    
                                                        $max = $items->stok + $record->qty;
                                                        return $max;
                                                    }  else {
                                                        $items = $item->where('id', $get('stock_id'))->first();
                                                        $max =  $items->stok;
                                                        return $max;
                                                    }                                                    
                                                                                          
                                                })                                                                                                                                                                                                                           
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
                                    ->mutateRelationshipDataBeforeFillUsing(function (array $data): array {
                                        $data['jumlah'] = number_format($data['qty'] * ($data['hjual'] - $data['disc']), 0, '', '.');
                                 
                                        return $data;
                                    })                                    
                                    ->defaultItems(1)
                                    ->columns([
                                        'md' => 10
                                    ])
                                    ->columnSpan('full')
                            ]),
                        Forms\Components\Card::make()                                                                                              
                            ->schema([                            
                            Forms\Components\TextInput::make('totaldp')
                                ->label('Uang Muka / DP')                                                                    
                                ->disabled()
                                ->dehydrated()
                                ->default(0), 
                            Forms\Components\TextInput::make('subtotal')
                                ->label('Subtotal')                                    
                                ->disabled()
                                ->dehydrated()
                                ->required(),                             
                            Forms\Components\TextInput::make('tot_disc')
                                ->label('Total Discount')                                    
                                ->disabled()
                                ->dehydrated()
                                ->required(),                                                           
                            Forms\Components\TextInput::make('tot_har')
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
                            Forms\Components\TextInput::make('sisa')
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
                Tables\Columns\IconColumn::make('is_pending')
                // Tables\Columns\BadgeColumn::make('is_pending ?')
                    ->icon(fn (string $state): string => match ($state) {
                        '1'   => 'heroicon-o-check-circle',
                        '0'   => 'heroicon-o-x-circle'
                    })
                    ->boolean(),                    
                    // ->color(fn (string $state): string => match ($state) {                        
                    //     'True' => 'warning',
                    //     'False'=> 'info',                        
                    // })
                    // ->label('Pending ?'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()->hiddenLabel()->tooltip('Detail'),  
                Tables\Actions\Action::make('print')
                    ->hiddenLabel()
                    ->tooltip('Print')
                    ->url(fn ($record) => '/print/fakturjual/'.$record->id)
                    ->color('primary')
                    ->icon('heroicon-o-printer')                    
                    ->openUrlInNewTab(), 
                Tables\Actions\Action::make('pelunasan')->hiddenLabel()->tooltip('Pelunasan')
                    ->label('Pelunasan')
                    ->color('warning')
                    ->icon('heroicon-o-queue-list')                    
                    ->form([  
                        Forms\Components\Hidden::make('jual_id')                                                        
                            ->default(fn(Jual $record): string => $record->id),                      
                        Forms\Components\TextInput::make('code')
                            ->label('Faktur Penjualan')
                            ->disabled()
                            ->dehydrated()
                            ->default(fn(Jual $record): string => $record->code),
                        Forms\Components\TextInput::make('out_sisa')
                            ->label('Sisa Pembayaran')
                            ->disabled()                        
                            ->default(fn(Jual $record): string => number_format($record->sisa, '0', '', '.')),
                        Forms\Components\Hidden::make('sisa')
                            ->default(fn(Jual $record) => $record->sisa),
                        Forms\Components\Hidden::make('tot_bayar')
                            ->default(fn(Jual $record) => $record->tot_bayar),
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Pelunasan')
                            ->default(now())
                            ->required(),
                        Forms\Components\TextInput::make('bayar')
                            ->label('Nominal Pembayaran')                            
                            ->required(),
                    ])
                    ->action(function (array $data): void {                        
                        $record[] = array();
                        $record['user_id'] = auth()->user()->id;
                        $record['jual_id'] = $data['jual_id'];
                        $record['tanggal'] = $data['tanggal'];
                        $record['bayar']   = $data['bayar'];                        
                        $sisa = $data['sisa'] - $data['bayar'];
                        $bayar = $data['tot_bayar'] + $data['bayar'];
                        if ($sisa > 0) {
                            $status = 'Piutang';
                        } else {
                            $status = 'Lunas';
                        }
                        PiutangPenjualan::Create($record);
                        Jual::where('id', $data['jual_id'])->update([
                            'sisa'      => $sisa,
                            'status'    => $status,
                            'tot_bayar' => $bayar,
                        ]);
                    })->visible(fn (Jual $record): bool => $record->status === 'Piutang')
                    ->modalWidth(MaxWidth::Medium),             
                Tables\Actions\EditAction::make()->hiddenLabel()->tooltip('Edit'),
                ])                                
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
        $selectedProducts = collect($get('detailJual'))->filter(fn($item) => !empty($item['qty']) && !empty($item['hjual']));        
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
        $set('tot_disc', number_format($totaldiscount, 0, '', '.'));        
        $set('tot_har', number_format($total, 0, '', '.'));        
        

    }

    public static function updateSisaPembayaran(Forms\Get $get, Forms\Set $set): void
    {            
        if (!empty($get('tot_har'))) {            
            $sisa = (int)str_replace('.', '', $get('tot_har')) - (int)str_replace('.', '', $get('totaldp')) - $get('tot_bayar');                     
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
        $set('sisa', number_format($sisa, 0, '', '.'));                  
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
