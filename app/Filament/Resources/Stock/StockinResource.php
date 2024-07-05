<?php

namespace App\Filament\Resources\Stock;

use App\Filament\Resources\Stock\StockinResource\Pages;
use App\Filament\Resources\Stock\StockinResource\RelationManagers;
use App\Models\Products\Products;
use App\Models\Products\StockCategories;
use App\Models\Products\Stockin;
use App\Models\Products\Stock;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

class StockinResource extends Resource
{
    protected static ?string $model = Stockin::class;

    protected static ?string $navigationGroup = 'Stock';

    protected static ?string $pluralModelLabel = 'Stockin';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square';

    public static function form(Form $form): Form
    {
        $stock = Stock::get();
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->label('Kode Stockin')
                                    ->default(function() {
                                        $date = Carbon::now()->format('my');
                                        $last = Stockin::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
                                        if ($last != null) {  
                                            // foreach($q->result() as $k){
                                            //     $tmp = substr($k->kd_max, 10,2)+1;
                                            //     $kd = sprintf("%02s", $tmp);
                                            // }                                                                                                 
                                            $tmp = substr($last, 8, 4)+1;
                                            // return $tmp;
                                            return "STI-".$date.sprintf("%03s", $tmp);                                                                            
                                        } else {
                                            return "STI-".$date."001";
                                        }
                                    })
                                    ->readonly()
                                    ->required()
                                    ->columnSpan(1),                                
                                Forms\Components\DatePicker::make('tanggal')
                                    ->default(now())
                                    ->required(),
                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori')                                  
                                    ->options(StockCategories::where('jenis', '=', 'Stockin')->pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),
                                Forms\Components\TextInput::make('sumber')
                                    ->label('Sumber Stok')
                                    ->required(),
                                Forms\Components\TextArea::make('keterangan')
                                    ->label('Keterangan')
                                    ->columnSpan(2),
                            ])->columns(3),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Products'),
                                Repeater::make('detailStockin')
                                    ->label('Detail Items')                                                                    
                                    ->relationship()
                                    ->schema([                                        
                                        Forms\Components\Select::make('stock_id')
                                            ->label('SKU')                                            
                                            ->options(                                                
                                                $stock->mapWithKeys(function (Stock $stock) {
                                                    return [$stock->id => sprintf('%s-%s', $stock->product->code, $stock->code)];
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
                                                    $set('name', $stock->product->name);                                                    
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
                    ->label('Invoice')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')            
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
            'index' => Pages\ListStockins::route('/'),
            'create' => Pages\CreateStockin::route('/create'),
            'edit' => Pages\EditStockin::route('/{record}/edit'),
        ];
    }
}
