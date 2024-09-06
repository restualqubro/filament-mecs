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
                                    Forms\Components\Select::make('stock_id')
                                    ->label('Kode Stok')
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
                                        ->afterStateUpdated(function($state, callable $set) {
                                            $details = DetailService::where('servicecatalog_id', $state)->first();
                                            if ($details) {                                                                                                                                                                                                                
                                                $set('biaya', $details->biaya);                                                
                                                $set('disc', $details->disc);                                                
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
        $selectedProducts = collect($get('detailRetur'))->filter(fn($item) => !empty($item['qty']) && !empty($item['hjual']) && !empty($item['disc']));                
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
        $set('totaldiscount', number_format($totaldiscount, 0, '', '.'));        
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
