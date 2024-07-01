<?php

namespace App\Filament\Clusters\Products\Resources;

use App\Filament\Clusters\Products;
use App\Filament\Clusters\Products\Resources\StockResource\Pages;
use App\Filament\Clusters\Products\Resources\StockResource\RelationManagers;
use App\Models\Products\Stock;
use App\Models\Products\Products as Items;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Support\Carbon;

class StockResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Products::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Code Generator')
                    ->schema(                        
                        [          
                        Forms\Components\Hidden::make('code')
                            ->default(function() {
                                $date = Carbon::now()->format('my');
                                return $date."01";
                            }),         
                        Forms\Components\Select::make('product_id')
                            ->label('Kode Items')
                            ->required()
                            ->options(Items::all()->pluck('name', 'id'))
                            ->searchable(),                                                           
                        Forms\Components\TextInput::make('hbeli')
                            ->label('Harga Beli')
                            ->numeric()
                            ->required(),
                            Forms\Components\TextInput::make('stok')
                            ->label('Stok Awal')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('supplier_warranty')
                            ->label('Garansi Supplier')
                            ->required()
                            ->numeric(),                                               
                    ])->columns('2')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.code')
                    ->label('Kode Items')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Nama Items')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Stok'),                                    
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok'),                
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListStocks::route('/'),
            // 'create' => Pages\CreateStock::route('/create'),
            // 'edit' => Pages\EditStock::route('/{record}/edit'),
        ];
    }
}
