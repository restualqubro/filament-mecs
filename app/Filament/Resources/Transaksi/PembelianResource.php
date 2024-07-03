<?php

namespace App\Filament\Resources\Transaksi;

use App\Filament\Resources\Transaksi\PembelianResource\Pages;
use Filament\Forms\Components\Repeater;
use App\Models\Transaksi\Beli as Pembelian;
use App\Models\Products\Products;
use App\Models\Connect\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $pluralModelLabel = 'Pembelian';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        $products = Products::get();
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->label('Faktur Pembelian')
                                    ->default(function() {
                                        $date = Carbon::now()->format('my');
                                        $last = Pembelian::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
                                        if ($last != null) {  
                                            // foreach($q->result() as $k){
                                            //     $tmp = substr($k->kd_max, 10,2)+1;
                                            //     $kd = sprintf("%02s", $tmp);
                                            // }                                                                                                 
                                            $tmp = substr($last, 8, 4)+1;
                                            // return $tmp;
                                            return "FKB-".$date.sprintf("%03s", $tmp);                                                                            
                                        } else {
                                            return "FKB-".$date."001";
                                        }
                                    })
                                    ->readonly()
                                    ->required()
                                    ->columnSpan(1),                                
                                Forms\Components\DatePicker::make('tanggal')
                                    ->default(now())
                                    ->required(),                                                               
                                Forms\Components\Select::make('supplier_id')
                                    ->label('Supplier')
                                    ->required()
                                    ->options(Supplier::all()->pluck('name','id')),
                                Forms\Components\TextInput::make('ongkir')
                                    ->label('Ongkos Kirim')
                                    ->required(),
                                Forms\Components\TextInput::make('tot_har')
                                    ->label('Ongkos Kirim')
                                    ->required(),
                            ])->columns(3),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Products'),
                                Repeater::make('detailBeli')
                                    ->label('Detail Items')                                                                    
                                    ->relationship()
                                    ->schema([                                        
                                        Forms\Components\Select::make('product_id')
                                            ->label('Kode Product')                                            
                                            // ->options(fn(Forms\Get $get): Collection => Stock::query()
                                            //                                 ->where('product_id', $get('product_id'))
                                            //                                 ->pluck('code', 'id'))
                                            ->options(                                                
                                                $products->mapWithKeys(function (Products $products) {
                                                    return [$products->id => sprintf($products->code)];
                                                })
                                                )
                                                    // foreach($stock as $items)
                                                    // {
                                                    //     return items;
                                                    // }                                            
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
                                                $products = Products::find($state);
                                                if ($products) {                                                    
                                                    $set('name', $products->product->name);                                                    
                                                }
                                            })
                                            ->columnSpan([
                                                'md' => 3
                                            ]),
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Item')                                                                                 
                                            ->disabled()
                                            ->dehydrated()
                                            ->columnSpan([
                                                'md' => 5   
                                            ]),                                          
                                        Forms\Components\TextInput::make('qty')                                            
                                            ->numeric()                                            
                                            ->columnSpan([
                                                'md' => 2
                                            ]),                                        
                                    ])
                                    ->defaultItems(1)
                                    ->columns([
                                        'md' => 10
                                    ])
                                    ->columnSpan('full')
                            ]),
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
