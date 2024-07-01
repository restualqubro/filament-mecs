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

class StockinResource extends Resource
{
    protected static ?string $model = Stockin::class;

    protected static ?string $navigationGroup = 'Stock';

    protected static ?string $pluralModelLabel = 'Stockin';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('id')
                                    ->label('Kode Stockin')
                                    ->default('Invoice123123')
                                    ->readonly()
                                    ->required()
                                    ->columnSpan(1),                                
                                Forms\Components\DatePicker::make('tanggal')
                                    ->default(now())
                                    ->required(),
                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori')
                                    // ->relationship('nama','Nama Kategori')
                                    // ->createOptionForm([
                                    //     Forms\Components\TextInput::make('name')
                                    //         ->required(),                                        
                                    // ]),
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
                                    ->relationship()
                                    ->schema([                                        
                                        Forms\Components\Select::make('product_id')
                                            ->label('Product')                                            
                                            ->options(Products::all()->pluck('code', 'id'))
                                            ->required()
                                            ->searchable()
                                            ->reactive()
                                            // ->afterStateUpdated(function($state, callable $set) {
                                            //     $product = Products::find($state);
                                            //     if ($product) {
                                            //         $set('stok', $product->stok);                                                    
                                            //         $set('name', $product->name);                                                    
                                            //     }
                                            // })
                                            ->columnSpan([
                                                'md' => 2
                                            ]),
                                        Forms\Components\Select::make('stock_id')
                                            ->label('SKU')                                            
                                            ->options(fn(Forms\Get $get): Collection => Stock::query()
                                                                            ->where('product_id', $get('product_id'))
                                                                            ->pluck('code', 'id'))
                                            ->required()
                                            ->searchable()
                                            ->reactive()
                                            ->afterStateUpdated(function($state, callable $set) {
                                                $stock = Stock::find($state);
                                                if ($stock) {                                                    
                                                    $set('name', $stock->product->name);                                                    
                                                }
                                            })
                                            ->columnSpan([
                                                'md' => 2
                                            ]),
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Item')                                                                                 
                                            ->disabled()
                                            ->columnSpan([
                                                'md' => 4
                                            ]),                                        
                                        Forms\Components\TextInput::make('qty')                                            
                                            ->numeric()                                            
                                            ->columnSpan([
                                                'md' => 2
                                            ]),
                                        // Forms\Components\Hidden::make('product_price')
                                        //     ->disabled()
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
            'index' => Pages\ListStockins::route('/'),
            'create' => Pages\CreateStockin::route('/create'),
            'edit' => Pages\EditStockin::route('/{record}/edit'),
        ];
    }
}
