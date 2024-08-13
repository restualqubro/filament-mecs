<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\ServiceSelesaiResource\Pages;
use App\Filament\Resources\Services\ServiceSelesaiResource\RelationManagers;
use App\Models\Service\Selesai;
use App\Models\Service\Data;
use App\Models\Connect\Customers;
use App\Models\Products\Stock;
use App\Models\Service\Catalog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\RawJs;

class ServiceSelesaiResource extends Resource
{    
    protected static ?string $model = Selesai::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $navigationGroup = 'Service';

    protected static ?string $pluralModelLabel = 'Service Selesai';

    protected static ?string $slug = 'service-selesai';

    public static function form(Form $form): Form
    {
        $stock = Stock::get();
        $catalog = Catalog::get();
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
                                                    $last = Selesai::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
                                                    if ($last != null) {                                                                                            
                                                        $tmp = substr($last, 8, 4)+1;
                                                        return "FKS-".$date.sprintf("%03s", $tmp);                                                                            
                                                    } else {
                                                        return "FKS-".$date."001";
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
                                            Forms\Components\Select::make('service_id')
                                                ->label('Kode Service')
                                                ->options(
                                                    Data::all()->where('status', 'Proses')->pluck('code', 'id')
                                                )
                                                ->live()
                                                ->afterStateUpdated(function($state, Forms\Get $get, Forms\Set $set) {
                                                    $service = Data::find($state);
                                                    if ($service) 
                                                    {
                                                        $set('service.customer.name', $service->customer->name);
                                                        $set('service.merk', $service->merk);
                                                        $set('service.seri', $service->seri);
                                                    }
                                                })
                                                ->columnSpan(2),                                                                   
                                        ])->columns(6),                                
                                    Forms\Components\Group::make()
                                    ->schema([                                        
                                        Forms\Components\TextInput::make('service.customer.name')
                                            ->disabled(),
                                        Forms\Components\TextInput::make('service.merk')
                                            ->disabled(),
                                        Forms\Components\TextInput::make('service.seri')
                                            ->disabled()
                                    ])
                                    ->columns(3)
                                ]),  
                            ]),                      
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
                                                    $set('products_hjual', $stock->product->hjual);
                                                    $set('products_hbeli', $stock->hbeli);
                                                }
                                            })                                           
                                            ->columnSpan([
                                                'md' => 4
                                            ]),                                 
                                        Forms\Components\Hidden::make('products_hbeli'),
                                        Forms\Components\TextInput::make('products_hjual')                                            
                                            ->label('Harga')
                                            ->disabled()                                            
                                            ->columnSpan([
                                                'md' => 1
                                            ]),                                        
                                        Forms\Components\TextInput::make('products_disc')                                            
                                            ->label('Discount')
                                            ->numeric()  
                                            ->mask(RawJs::make('$money($input'))
                                            ->stripCharacters('.')  
                                            ->required()                                        
                                            ->columnSpan([
                                                'md' => 2
                                            ]),
                                        Forms\Components\TextInput::make('products_qty') 
                                            ->label('Qty')   
                                            ->numeric()    
                                            ->required()                                                                                                                                                                                                                            
                                            ->columnSpan([
                                                'md' => 1
                                            ])
                                            ->live()
                                            ->afterStateUpdated(
                                                function (Forms\Get $get, Forms\Set $set) {
                                                    $disc = str_replace(',', '', $get('products_disc'));
                                                    $qty = $get('products_qty');
                                                    $hjual = $get('products_hjual');
                                                    $hbeli = $get('products_hbeli');
                                                    $jumlah = $qty * ($hjual - $disc);
                                                    $profit = (($hjual - $disc) - $hbeli) * $qty;
                                                    $set('products_profit', $profit);
                                                    $set('products_jumlah', number_format($jumlah, 0, '', '.'));
                                                }
                                            ), 
                                        Forms\Components\Hidden::make('products_profit'),                                       
                                        Forms\Components\TextInput::make('products_jumlah') 
                                            ->label('Jumlah')                                           
                                            ->disabled()                                                                                    
                                            ->columnSpan([
                                                'md' => 2
                                            ]),
                                    ])
                                    ->live()
                                    // After adding a new row, we need to update the totals
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                        self::updateTotalProducts($get, $set);
                                    })
                                    // After deleting a row, we need to update the totals
                                    ->deleteAction(
                                        fn(Forms\Components\Actions\Action $action) => $action->after(fn(Forms\Get $get, Forms\Set $set) => self::updateTotalProducts($get, $set)),
                                    )
                                    ->defaultItems(1)
                                    ->columns([
                                        'md' => 10
                                    ])
                                    ->columnSpan('full')
                            ]),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Service Catalog'),
                                Repeater::make('detailService')
                                    ->label('Detail Catalog Service')                                                                    
                                    ->relationship()
                                    ->collapsible()
                                    ->schema([                                        
                                        Forms\Components\Select::make('servicecatalog_id')
                                            ->label('Kode Catalog')                                                                                        
                                            ->options(Catalog::all()->pluck('name', 'id'))                                                                                            
                                            ->required()
                                            ->searchable()
                                            ->reactive()
                                            ->disableOptionWhen(function ($value, $state, Forms\Get $get) {
                                                return collect($get('../*.servicecatalog_id'))
                                                    ->reject(fn($id) => $id == $state)
                                                    ->filter()
                                                    ->contains($value);
                                            }) 
                                            ->afterStateUpdated(function($state, callable $set) {
                                                $service = Catalog::find($state);
                                                if ($service) {                                                    
                                                    $set('service_biaya', $service->biaya_max);                                                                  
                                                }
                                            })                                           
                                            ->columnSpan([
                                                'md' => 4
                                            ]),                                  
                                        Forms\Components\TextInput::make('service_biaya')
                                            ->label('Biaya')
                                            ->disabled()                                            
                                            ->columnSpan([
                                                'md' => 2
                                            ]),                                                                                    
                                        Forms\Components\TextInput::make('service_disc')                                            
                                            ->label('Discount')
                                            ->numeric()    
                                            ->required()                                        
                                            ->columnSpan([
                                                'md' => 1
                                            ]),
                                        Forms\Components\TextInput::make('service_qty') 
                                            ->label('Qty')   
                                            ->numeric()    
                                            ->required()                                                                                                                                                                                                                            
                                            ->columnSpan([
                                                'md' => 1
                                            ])
                                            ->live()
                                            ->afterStateUpdated(
                                                function (Forms\Get $get, Forms\Set $set) {
                                                    $servicecatalog_id = $get('servicecatalog_id');
                                                    $catalog = Catalog::find($servicecatalog_id);
                                                    if($catalog)
                                                    {                                                        
                                                        $disc = $get('service_disc');
                                                        $qty = $get('service_qty');                                                        
                                                        $jumlah = $qty * ($catalog->biaya_max - $disc);
                                                        $set('service_jumlah', number_format($jumlah, 0, '', '.'));
                                                    }                                                                                                        
                                                }
                                            ),                                                                              
                                        Forms\Components\TextInput::make('service_jumlah') 
                                            ->label('Jumlah')                                           
                                            ->disabled()                                                                                    
                                            ->columnSpan([
                                                'md' => 2
                                            ]),
                                    ])
                                    ->live()
                                    // After adding a new row, we need to update the totals
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                        self::updateTotalService($get, $set);
                                    })
                                    // After deleting a row, we need to update the totals
                                    ->deleteAction(
                                        fn(Forms\Components\Actions\Action $action) => $action->after(fn(Forms\Get $get, Forms\Set $set) => self::updateTotalService($get, $set)),
                                    )
                                    ->defaultItems(1)
                                    ->columns([
                                        'md' => 10
                                    ])
                                    ->columnSpan('full')
                            ]),
                    Forms\Components\Card::make()                                                                                              
                        ->schema([  
                            Forms\Components\Group::make()
                                ->schema([
                                    Forms\Components\TextInput::make('subtotal_products')
                                        ->label('Subtotal Products')                                    
                                        ->disabled()
                                        ->dehydrated()
                                        ->required(),                                              
                                    Forms\Components\TextInput::make('totaldiscount_products')
                                        ->label('Total Discount Products')                                    
                                        ->disabled()
                                        ->dehydrated()
                                        ->required(),     
                                    Forms\Components\TextInput::make('subtotal_service')
                                        ->label('Subtotal Service')                                    
                                        ->disabled()
                                        ->dehydrated()
                                        ->required(),                      
                                    Forms\Components\TextInput::make('totaldiscount_service')
                                        ->label('Total Discount Service')                                    
                                        ->disabled()
                                        ->dehydrated()
                                        ->required(),                                                       
                                    Forms\Components\TextInput::make('subtotal_component')
                                        ->label('Total Komponen')                                    
                                        ->disabled()
                                        ->dehydrated()
                                        ->required(),                                                                                                                           
                                ])->columns(5),
                            Forms\Components\Group::make()
                                ->schema([
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
                                    Forms\Components\Hidden::make('sisa'),                                                                                  
                                    Forms\Components\TextInput::make('out_sisa')
                                        ->label('Sisa Pembayaran')                                
                                        ->disabled()
                                        ->dehydrated(),
                                    Forms\Components\Hidden::make('status')                                
                                ])->columns(3),             
                            ])                           
                ])->columnSpan('full')            
        ]);
    }

    public static function updateTotalProducts(Forms\Get $get, Forms\Set $set): void
    {
        // Retrieve all selected products and remove empty rows
        $selectedProducts = collect($get('detailJual'))->filter(fn($item) => !empty($item['products_qty']) && !empty($item['products_hjual']) && !empty($item['products_disc']));                
        $subtotal = 0;
        $totaldiscount = 0;
        $total = 0;        
        foreach($selectedProducts as $item) {
            $subtotal += $item['products_hjual'] * $item['products_qty'];
            $totaldiscount += str_replace(',', '', $item['products_disc']) * $item['products_qty'];            
        }                              
        $total = $subtotal - $totaldiscount;
        // Update the state with the new values
        $set('subtotal_products', number_format($subtotal, 0, '', '.'));
        $set('totaldiscount_products', number_format($totaldiscount, 0, '', '.'));
        // $set('out_tot_disc', number_format($totaldiscount, 0, '', '.'));
        // $set('tot_har', $total);
        // $set('out_tot_har', number_format($total, 0, '', '.'));                
        

    }

    public static function updateTotalService(Forms\Get $get, Forms\Set $set): void
    {
        // Retrieve all selected products and remove empty rows
        $selectedCatalog = collect($get('detailService'))->filter(fn($item) => !empty($item['service_qty']) && !empty($item['service_biaya']) && !empty($item['service_disc']));                
        $subtotal = 0;
        $totaldiscount = 0;
        $total = 0;        
        foreach($selectedCatalog as $item) {
            $subtotal += $item['service_biaya'] * $item['service_qty'];
            $totaldiscount += $item['service_disc'] * $item['service_qty'];            
        }                              
        $total = $subtotal - $totaldiscount;
        // Update the state with the new values
        $set('subtotal_service', number_format($subtotal, 0, '', '.'));
        $set('totaldiscount_service', number_format($totaldiscount, 0, '', '.'));
        // $set('tot_disc', $totaldiscount);
        // $set('out_tot_disc', number_format($totaldiscount, 0, '', '.'));
        // $set('tot_har', $total);
        // $set('out_tot_har', number_format($total, 0, '', '.'));                
        

    }

    public static function updateTotalComponent(Forms\Get $get, Forms\Set $set): void
    {
        // Retrieve all selected products and remove empty rows
        $selectedProducts = collect($get('detailJual'))->filter(fn($item) => !empty($item['qty']) && !empty($item['hjual']) && !empty($item['disc']));                
        $subtotal = 0;
        $totaldiscount = 0;
        $total = 0;        
        foreach($selectedProducts as $item) {
            $subtotal += $item['hjual'] * $item['qty'];
            $totaldiscount += $item['disc'] * $item['qty'];            
        }                              
        $total = $subtotal - $totaldiscount;
        // Update the state with the new values
        $set('subtotal', number_format($subtotal, 0, '', '.'));
        // $set('tot_disc', $totaldiscount);
        // $set('out_tot_disc', number_format($totaldiscount, 0, '', '.'));
        // $set('tot_har', $total);
        // $set('out_tot_har', number_format($total, 0, '', '.'));                
        

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
            'index' => Pages\ListServiceSelesais::route('/'),
            'create' => Pages\CreateServiceSelesai::route('/create'),
            'edit' => Pages\EditServiceSelesai::route('/{record}/edit'),
        ];
    }
}
