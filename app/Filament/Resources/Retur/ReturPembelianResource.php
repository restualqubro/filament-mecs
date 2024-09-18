<?php

namespace App\Filament\Resources\Retur;

use App\Filament\Resources\Retur\ReturPembelianResource\Pages;
use App\Filament\Resources\Retur\ReturPembelianResource\RelationManagers;
use App\Models\Retur\ReturBeli;
use App\Models\Transaksi\Beli;
use App\Models\Retur\DetailReturBeli;
use App\Models\Transaksi\DetailBeli;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ReturPembelianResource extends Resource
{
    protected static ?string $model = ReturBeli::class;

    protected static ?string $slug = 'buy';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Retur';
    
    protected static ?string $pluralModelLabel = 'Retur Pembelian';   

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
                                                    $last = ReturBeli::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
                                                    if ($last != null) {                                                                                            
                                                        $tmp = substr($last, 8, 4)+1;
                                                        return "FRB-".$date.sprintf("%03s", $tmp);                                                                            
                                                    } else {
                                                        return "FRB-".$date."001";
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
                                            Forms\Components\Select::make('beli_id')
                                                ->required()
                                                ->options(Beli::all()->pluck('code', 'id'))
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
                                            $beliid = $get('../../beli_id');                                                
                                            if ($beliid)
                                            {                                                    
                                                return DetailBeli::join('stock', 'detail_beli.stock_id', '=', 'stock.id')
                                                                    ->where('detail_beli.beli_id', $beliid)
                                                                    ->get()
                                                                    ->pluck('stock.product.name', 'stock.id');
                                            }
                                        }
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
                                            $details = DetailBeli::where('stock_id', $state)->first();
                                            if ($details) {                                                                                                                                                                                                                
                                                $set('hbeli', $details->hbeli);                                                
                                            }
                                        })                                           
                                        ->columnSpan([
                                            'md' => 5
                                        ]),                                                                                                             
                                    Forms\Components\TextInput::make('hbeli')                                            
                                        ->label('Harga')
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
                                        ->maxValue(function (DetailBeli $item, Forms\Get $get): int
                                                {      
                                                    $item = $item->where('beli_id', $get('../../beli_id'))
                                                                ->where('stock_id', $get('stock_id'))->first();                                                                                    
                                                    if ($item) {
                                                        $max = $item->qty;
                                                        return $max;
                                                    }                                                                                                                                                                                          
                                                })                                                     
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
        $selectedProducts = collect($get('detailRetur'))->filter(fn($item) => !empty($item['qty']) && !empty($item['hbeli']));                                
        $total = 0;        
        foreach($selectedProducts as $item) {
            $total += $item['hbeli'] * $item['qty'];            
        }                                      
        // Update the state with the new values        );        
        $set('totalharga', number_format($total, 0, '', '.'));                
        

    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReturPembelians::route('/'),
            'create' => Pages\CreateReturPembelian::route('/create'),
            'edit' => Pages\EditReturPembelian::route('/{record}/edit'),
        ];
    }
}
