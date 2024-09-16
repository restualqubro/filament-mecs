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
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Carbon\Carbon;
use Filament\Support\Enums\FontWeight;

class ServiceSelesaiResource extends Resource
{    
    protected static ?string $model = Selesai::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $navigationGroup = 'Service';

    protected static ?int $navigationSort = 5;

    protected static ?string $pluralModelLabel = 'Service Selesai';

    protected static ?string $slug = 'service-selesai';

    public static function form(Form $form): Form
    {
        $stock = Stock::get()->where('product.category.name', '!=', 'COMPONENT');
        $component = Stock::get()->where('product.category.name', 'COMPONENT');
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
                                            Forms\Components\TextInput::make('teknisi')                                                
                                                ->required()
                                                ->default(fn() => auth()->user()->name)
                                                ->disabled()
                                                ->columnSpan([
                                                    'md' => 2
                                                ]),                                                                                                            
                                            Forms\Components\TextInput::make('datein')
                                                ->label('Tanggal')
                                                ->disabled()
                                                ->default(Carbon::now()->format('d M Y'))                                                
                                                ->columnSpan(2),                                                  
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
                                                        $set('name', $service->customer->name);
                                                        $set('merk', $service->merk);
                                                        $set('seri', $service->seri);
                                                    }
                                                })
                                                ->columnSpan(2),                 
                                        ])->columns(6),                                
                                    Forms\Components\Group::make()
                                        ->schema([                                        
                                            Forms\Components\TextInput::make('name')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('merk')
                                                ->disabled(),
                                            Forms\Components\TextInput::make('seri')
                                                ->disabled()
                                        ])
                                        ->columns(3)
                                ]),  
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
                                                    $set('biaya', $service->biaya_max);                                                                  
                                                }
                                            })                                           
                                            ->columnSpan([
                                                'md' => 4
                                            ]),                                  
                                        Forms\Components\TextInput::make('biaya')
                                            ->label('Biaya')
                                            ->disabled() 
                                            ->dehydrated()                                           
                                            ->columnSpan([
                                                'md' => 2
                                            ]),                                                                                    
                                        Forms\Components\TextInput::make('service_disc')                                            
                                            ->label('Discount')
                                            ->numeric()                                             
                                            ->default(0)  
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
                                                        $jumlah = $get('service_qty') * ($catalog->biaya_max - $disc) ;
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
                                Forms\Components\Placeholder::make('Service Component'),
                                Repeater::make('detailComponent')
                                    ->label('Detail Component')                                                                    
                                    ->relationship()
                                    ->collapsible()
                                    ->schema([                                        
                                        Forms\Components\Select::make('stock_id')
                                            ->label('Kode Component')                                                                                        
                                            ->options($component->mapWithKeys(function (Stock $component) {
                                                return [$component->id => sprintf('%s-%s | %s', $component->product->code, $component->code, $component->product->name)];
                                            }))                                                                                                                                        
                                            ->searchable()
                                            ->reactive()
                                            ->disableOptionWhen(function ($value, $state, Forms\Get $get) {
                                                return collect($get('../*.stock_id'))
                                                    ->reject(fn($id) => $id == $state)
                                                    ->filter()
                                                    ->contains($value);
                                            }) 
                                            ->afterStateUpdated(function($state, callable $set) {
                                                $component = Stock::find($state);
                                                if ($component) {                                                    
                                                    $set('component_hbeli', $component->hbeli);                                                                  
                                                }
                                            })                                           
                                            ->columnSpan([
                                                'md' => 5
                                            ]),                                  
                                        Forms\Components\TextInput::make('component_hbeli')
                                            ->label('Harga Beli')
                                            ->disabled()                                            
                                            ->columnSpan([
                                                'md' => 2
                                            ]),                                                                                                                            
                                        Forms\Components\TextInput::make('component_qty') 
                                            ->label('Qty')   
                                            ->numeric()                                                                                                                                                                                                                                                                           
                                            ->columnSpan([
                                                'md' => 1
                                            ])
                                            ->live()
                                            ->afterStateUpdated(
                                                function (Forms\Get $get, Forms\Set $set) {
                                                    $stock_id = $get('stock_id');
                                                    $component = Stock::find($stock_id);
                                                    if($component)
                                                    {                                                                                                                
                                                        $qty = $get('component_qty');                                                        
                                                        $jumlah = $qty * $component->hbeli;
                                                        $set('component_jumlah', number_format($jumlah, 0, '', '.'));
                                                    }                                                                                                        
                                                }
                                            ),                                                                              
                                        Forms\Components\TextInput::make('component_jumlah') 
                                            ->label('Jumlah')                                           
                                            ->disabled()                                                                                    
                                            ->columnSpan([
                                                'md' => 2
                                            ]),
                                    ])
                                    ->live()
                                    // After adding a new row, we need to update the totals
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                        self::updateTotalComponent($get, $set);
                                    })
                                    // After deleting a row, we need to update the totals
                                    ->deleteAction(
                                        fn(Forms\Components\Actions\Action $action) => $action->after(fn(Forms\Get $get, Forms\Set $set) => self::updateTotalComponent($get, $set)),
                                    )
                                    ->defaultItems(1)
                                    ->columns([
                                        'md' => 10
                                    ])
                                    ->columnSpan('full')
                                    ->defaultItems(0)
                            ]),
                    Forms\Components\Card::make()                                                                                              
                        ->schema([  
                            Forms\Components\Group::make()
                                ->schema([                                       
                                    Forms\Components\TextInput::make('subtotal_service')
                                        ->label('Subtotal Service')                                    
                                        ->disabled()
                                        ->default(0)
                                        ->dehydrated()
                                        ->required(),                      
                                    Forms\Components\TextInput::make('totaldiscount_service')
                                        ->label('Total Discount Service')                                    
                                        ->disabled()
                                        ->default(0)
                                        ->dehydrated()
                                        ->required(),                                                       
                                    Forms\Components\TextInput::make('subtotal_component')
                                        ->label('Total Komponen')                                    
                                        ->disabled()
                                        ->default(0)
                                        ->dehydrated()
                                        ->required(),                                                                                                                           
                                    Forms\Components\TextInput::make('total')
                                        ->label('Total')                                    
                                        ->disabled()
                                        ->default(0)
                                        ->dehydrated()
                                        ->required(),
                                ])->columns(3),                                        
                            ])                           
                ])->columnSpan('full')            
        ]);
    }
  

    public static function updateTotalService(Forms\Get $get, Forms\Set $set): void
    {        
        $selectedCatalog = collect($get('detailService'))->filter(fn($item) => !empty($item['service_qty']) && !empty($item['biaya']));                
        $subtotal = 0;
        $totaldiscount = 0;
        $total = 0;        
        foreach($selectedCatalog as $item) {
            $subtotal = $subtotal +  $item['service_qty'] * $item['biaya'] ;
            $totaldiscount += $item['service_disc'] * $item['service_qty'];      
            $total = $subtotal - $totaldiscount;
        }                                          
        $set('subtotal_service', number_format($subtotal, 0, '', '.'));
        $set('totaldiscount_service', number_format($totaldiscount, 0, '', '.'));      
        $set('total', number_format($total, 0, '', '.'));            
    }

    public static function updateTotalComponent(Forms\Get $get, Forms\Set $set): void
    {        
        $selectedProducts = collect($get('detailComponent'))->filter(fn($item) => !empty($item['component_qty']) && !empty($item['component_hbeli']));                
        $subtotal = 0;                     
        foreach($selectedProducts as $item) {
            $subtotal += $item['component_hbeli'] * $item['component_qty'];              
        }                                      
        $set('subtotal_component', number_format($subtotal, 0, '', '.'));                   
    }
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service.code')
                    ->label('KODE SERVICE')
                    ->searchable(),
                Tables\Columns\TextColumn::make('service.customer.name')
                    ->label('NAMA CUSTOMER'),
                Tables\Columns\TextColumn::make('subtotal_service')
                    ->label('SUBTOTAL SERVICE')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('totaldiscount_service')
                    ->label('TOTAL DISCOUNT SERVICE')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('total')
                    ->label('TOTAL')
                    ->money('IDR'),
                
            ])
            ->filters([
                
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),                        
                    Tables\Actions\EditAction::make()
                        ->hidden(fn() => auth()->user()->roles->pluck('name')[0] === 'customer_service'),
                    Tables\Actions\Action::make('print')                    
                        ->url(fn ($record) => 'print/selesaireceipt/'.$record->id)
                        ->color('warning')
                        ->icon('heroicon-o-printer')                    
                        ->openUrlInNewTab(),                
                ])                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }    

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([     
                Section::make('Service Details')
                    ->schema([
                        TextEntry::make('service.code')
                            ->label('Kode Service')
                            ->weight(FontWeight::Bold), 
                        TextEntry::make('updated_at')
                            ->label('Last Updated'), 
                        TextEntry::make('service.customer.name')
                            ->label('Nama Customer'), 
                        TextEntry::make('service.merk')
                            ->label('Merk/Brand'),
                        TextEntry::make('service.seri')
                            ->label('Seti/Tipe'),                        
                        TextEntry::make('service.keluhan')
                            ->label('Keluhan'), 
                        TextEntry::make('subtotal_service')
                            ->label('Subtotal Service')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),                                                                                                 
                        TextEntry::make('totaldiscount_service')
                            ->label('Total Discount')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),    
                        TextEntry::make('total')
                            ->label('Total')
                            ->money('IDR')
                            ->weight(FontWeight::Bold), 
                        TextEntry::make('subtotal_component')
                            ->label('Total Component')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),    
                ])->columns(2),                                                             
                RepeatableEntry::make('detailService')
                    ->label('Detail Catalog Service')
                    ->schema([                                                                                                                                                                          
                        TextEntry::make('catalog.name')                        
                            ->label('Detail Perbaikan'),
                        TextEntry::make('biaya')
                            ->money('IDR')
                            ->label('Biaya'),
                        TextEntry::make('service_qty')
                            ->label('Qty'),
                        TextEntry::make('service_disc')
                            ->money('IDR')
                            ->label('Discount')
                    ])      
                    ->columns(2) 
                    ->columnSpan('full')                                 
                    ->grid(2),
                RepeatableEntry::make('detailComponent')
                    ->label('Detail Component Service')
                    ->schema([
                        TextEntry::make('stock.product.code')                        
                            ->label('Detail Perbaikan'),                                                                                                                                                                                                 
                        TextEntry::make('stock.product.name')                        
                            ->label('Detail Perbaikan'),                       
                        TextEntry::make('component_qty')                        
                            ->label('Qty'),
                        TextEntry::make('stock.product.hjual')                        
                            ->money('IDR')
                            ->label('Harga'),
                    ])      
                    ->columns(2) 
                    ->columnSpan('full')                                 
                    ->grid(2)        
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceSelesais::route('/'),
            'create' => Pages\CreateServiceSelesai::route('/create'),
            'view' => Pages\ViewDetails::route('/{record}/view'),
            'edit' => Pages\EditServiceSelesai::route('/{record}/edit'),
        ];
    }
}
