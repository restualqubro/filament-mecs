<?php

namespace App\Filament\Clusters\Products\Resources;

use App\Filament\Clusters\Products;
use App\Filament\Clusters\Products\Resources\ProductResource\Pages;
use App\Models\Products\Products as Stock;
use App\Models\products\ProductCategories;
use App\Models\products\ProductBrands;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Pages\SubNavigationPosition;

class ProductResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $cluster = Products::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Code Generator')
                    ->schema(                        
                        [                        
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->required()
                            ->options(ProductCategories::all()->pluck('name', 'id'))
                            ->searchable(),
                        Forms\Components\Select::make('brand_id')
                            ->label('Brand')
                            ->required()                    
                            ->options(ProductBrands::all()->pluck('name', 'id'))
                            ->searchable(),                                                    
                        Forms\Components\TextInput::make('code')
                        ->label('Kode Product')
                        ->required()
                        ->readOnly(),
                    ])->columns('3')
                    ->footerActions([
                        Forms\Components\Actions\Action::make('Generate')
                        ->action(function (Forms\Get $get, Forms\Set $set) { 
                            $category = ProductCategories::find($get('category_id'));                            
                            $brand = ProductBrands::find($get('brand_id'));                                                        
                            
                            if ($category === null || $brand === null) {
                                $set('id', "Generate Gagal!!");
                            } 
                            else {  
                                $last = Stock::where([
                                    ['category_id', '=', $category->id],
                                    ['brand_id', '=', $brand->id]
                                ])->max('id');
                                if ($last != null) {                                    
                                    $tmp = substr($last, 7, 3)+1;
                                    $set('id', $category->init."-".$brand->init.sprintf("%03s", $tmp));
                                } else {
                                    $set('id', $category->init."-".$brand->init."001");
                                }                
                            }
                        })->hidden(fn(string $operation): bool => $operation === 'view') 
                        
                    ])->hidden(fn(string $operation):bool => $operation === 'edit'),                      
                    Forms\Components\Section::make('Product Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Product')
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\Select::make('kondisi')
                            ->label('Kondisi')
                            ->required()
                            ->options([
                                'BARU'  => 'BARU',
                                'SECOND'=>  'SECOND',
                            ]),                                                
                        Forms\Components\TextInput::make('hress')
                            ->label('Harga Resell')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('hjual')
                            ->label('Harga Jual Umum')
                            ->numeric()
                            ->required(),                                                     
                        Forms\Components\TextInput::make('sale_warranty')
                            ->label('Garansi Customer')
                            ->numeric(),
                        Forms\Components\TextArea::make('description')
                            ->label('Keterangan/Description')
                            ->columnSpan(3),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('media')
                            ->label('Photo Product')
                            ->collection('products')
                            ->multiple()
                            ->columnSpan(3)
                    ])->columns('3')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Item')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Item')
                    ->searchable(),
                Tables\Columns\TextColumn::make('')
                    ->label('Stok'),                    
                Tables\Columns\TextColumn::make('hress')
                    ->label('Harga Resell')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('hjual')
                    ->label('Harga Umum')  
                    ->money('IDR')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->hiddenlabel()->tooltip('Detail'),
                Tables\Actions\EditAction::make()->hiddenlabel()->tooltip('Edit'),
                Tables\Actions\DeleteAction::make()->hiddenlabel()->tooltip('Delete'),
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
            'index' => Pages\ListProducts::route('/'),
            // 'create' => Pages\CreateProduct::route('/create'),
            // 'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
