<?php

namespace App\Filament\Resources\Retur;

use App\Filament\Resources\Retur\ReturServiceResource\Pages;
use App\Filament\Resources\Retur\ReturServiceResource\RelationManagers;
use App\Models\Retur\ReturService;
use App\Models\Service\Invoice;
use App\Models\Service\DetailService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ReturServiceResource extends Resource
{
    protected static ?string $model = ReturService::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $slug = 'serv';

    protected static ?string $navigationGroup = 'Retur';
    
    protected static ?string $pluralModelLabel = 'Retur Service';

    public static function form(Form $form): Form
    {
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
                                                ->label('Faktur Retur')
                                                ->default(function() {
                                                    $date = Carbon::now()->format('my');
                                                    $last = ReturService::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
                                                    if ($last != null) {                                                                                            
                                                        $tmp = substr($last, 8, 4)+1;
                                                        return "FRS-".$date.sprintf("%03s", $tmp);                                                                            
                                                    } else {
                                                        return "FRS-".$date."001";
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
                                            Forms\Components\Select::make('invoice_id')
                                                ->required()
                                                ->options(Invoice::all()->pluck('code', 'id'))
                                                ->columnSpan([
                                                    'md' => 2
                                                ]),                                                                                                               
                                        ])->columns(6)
                            ])
                        ]),                                                                                                                                                   
                    Forms\Components\Card::make()
                        ->schema([
                            Forms\Components\Placeholder::make('Products'),
                            Forms\Components\Repeater::make('detailRetur')
                                ->label('Detail Items')                                                                    
                                ->relationship()
                                ->collapsible()
                                ->schema([                                        
                                    Forms\Components\Select::make('servicecatalog_id')
                                    ->label('Items Service')
                                    ->options(
                                        function (Forms\Get $get) {
                                            $invoiceid = $get('../../invoice_id');                                                
                                            if ($invoiceid)
                                            {                 
                                                $selesaiid = Invoice::find($invoiceid)->pluck('selesai_id');
                                                return DetailService::join('service_selesai', 'detail_service_catalog.selesai_id', '=', 'service_selesai.id')
                                                                    ->where('service_selesai.id', $selesaiid)
                                                                    ->get()
                                                                    ->pluck('catalog.name', 'catalog.id');
                                            }
                                        }
                                    )                                                                                           
                                        ->required()
                                        ->searchable()
                                        ->reactive()
                                        ->disableOptionWhen(function ($value, $state, Forms\Get $get) {
                                            return collect($get('../*.servicecatalog_id'))
                                                ->reject(fn($id) => $id == $state)
                                                ->filter()
                                                ->contains($value);
                                        }) 
                                        ->afterStateUpdated(function($state, callable $set, callable $get) {
                                            $invoiceid = $get('../../invoice_id');                                                
                                            if ($invoiceid)
                                            {                 
                                                $selesaiid = Invoice::find($invoiceid)->pluck('selesai_id');
                                                $details = DetailService::where('servicecatalog_id', $state)
                                                        ->where('selesai_id', $selesaiid)->first();                                                
                                                if ($details) {                                                               
                                                    $set('biaya', $details->biaya);                                                
                                                    $set('disc', $details->service_disc);                                                
                                                }
                                            }                                            
                                            
                                        })                                           
                                        ->columnSpan([
                                            'md' => 5
                                        ]),                                                                                                             
                                    Forms\Components\TextInput::make('biaya')                                            
                                        ->label('Biaya')
                                        ->disabled()                                            
                                        ->columnSpan([
                                            'md' => 1
                                        ]),                                                                            
                                    Forms\Components\TextInput::make('disc')                                            
                                        ->label('Discount')
                                        ->disabled()     
                                        ->minValue(0)                                                                          
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
                                        ->maxValue(function (DetailService $item, Forms\Get $get): int
                                                {      
                                                    $selesai = Invoice::find($get('../../invoice_id'))->selesai_id;                                                    
                                                    $item = $item->where('selesai_id', $selesai)
                                                                ->where('servicecatalog_id', $get('servicecatalog_id'))->first();                                                                                                                                        
                                                    if ($item) {
                                                        $max = $item->service_qty;
                                                        return $max;
                                                    }                                                                                                                                                                                          
                                                })
                                        ->live()
                                        ->afterStateUpdated(
                                            function (Forms\Get $get, Forms\Set $set) {                                                
                                                $qty = $get('qty');
                                                $biaya = $get('biaya');
                                                $disc = $get('disc');
                                                $jumlah = $qty * ($biaya - $disc);                                                                                                        
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
                            Forms\Components\TextInput::make('totalharga')
                                ->label('Total Harga Retur')                                
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

    public static function updateTotalHarga(Forms\Get $get, Forms\Set $set): void
    {
        // Retrieve all selected products and remove empty rows
        $selectedProducts = collect($get('detailRetur'))->filter(fn($item) => !empty($item['qty']) && !empty($item['biaya']));                
        $subtotal = 0;
        $totaldiscount = 0;
        $total = 0;        
        foreach($selectedProducts as $item) {
            $subtotal += $item['biaya'] * $item['qty'];
            $totaldiscount += $item['disc'] * $item['qty'];            
        }                              
        $total = $subtotal - $totaldiscount;
        // Update the state with the new values
        $set('totalharga', number_format($total, 0, '', '.'));                
        

    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReturServices::route('/'),
            'create' => Pages\CreateReturService::route('/create'),
            'edit' => Pages\EditReturService::route('/{record}/edit'),
        ];
    }
}
