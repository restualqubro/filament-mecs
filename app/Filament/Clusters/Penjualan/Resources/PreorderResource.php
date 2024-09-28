<?php

namespace App\Filament\Clusters\Penjualan\Resources;

use App\Filament\Clusters\Penjualan;
use App\Filament\Clusters\Penjualan\Resources\PreorderResource\Pages;
use App\Filament\Clusters\Penjualan\Resources\PreorderResource\RelationManagers;
use App\Models\Transaksi\Preorder;
use App\Models\Connect\Customers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Support\Carbon;

class PreorderResource extends Resource
{
    protected static ?string $model = Preorder::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-arrow-down';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;    

    protected static ?string $cluster = Penjualan::class;

    public static function form(Form $form): Form
    {        
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([                        
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('code')
                                            ->label('Faktur Preorder')
                                            ->default(function() {
                                                $date = Carbon::now()->format('my');
                                                $last = Preorder::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
                                                if ($last != null) {                                                                                            
                                                    $tmp = substr($last, 8, 4)+1;
                                                    return "FPO-".$date.sprintf("%03s", $tmp);                                                                            
                                                } else {
                                                    return "FPO-".$date."001";
                                                }
                                            })
                                            ->readonly()
                                            ->required()
                                            ->disabled()
                                            ->dehydrated()
                                            ->columnSpan([
                                                'md' => 3
                                            ]),                                                                                                                                     
                                        Forms\Components\Select::make('customer_id')
                                            ->label('Customer')
                                            ->required()
                                            ->options(Customers::all()->pluck('name','id'))
                                            ->columnSpan([
                                                'md' => 3
                                            ]),
                                        Forms\Components\TextInput::make('nominal')                                            
                                            ->required()
                                            ->numeric()                                            
                                            ->label('Nominal DP')
                                            ->columnSpan([
                                                'md' => 3
                                            ]),                                                               
                                        Forms\Components\TextInput::make('estimasi')
                                            ->label('Estimasi Waktu')
                                            ->required()                                            
                                            ->columnSpan([
                                                'md' => 3
                                            ]),                                                                                  
                                    ])->columns(6),                                
                                Forms\Components\Group::make()
                                ->schema([
                                    Forms\Components\Textarea::make('description')
                                        ->label('Description / Keterangan (Masukkan Item dan Detail Item Preorder)')
                                        ->rows(1)                                                                    
                                ])
                                ->columns('full')
                            ])->columnSpan(6),                                                                               
                    ])->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Preorder')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('nominal')
                    ->label('Nominal DP')
                    ->money('IDR'),
                    Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Preorder')
                    ->date(),
                Tables\Columns\TextColumn::make('estimasi')                    
                    ->suffix(' Hari'),
                Tables\Columns\TextColumn::make('status')                    
                    ->badge()
                    ->colors([
                        'danger'    => 'Cancel',
                        'success'   => 'Selesai',
                        'gray'      => 'Baru'
                    ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('print')                    
                        ->url(fn ($record) => '/print/fakturpreorder/'.$record->id)
                        ->color('warning')
                        ->icon('heroicon-o-printer')                    
                        ->openUrlInNewTab(),    
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('cancel')
                        ->label('Cancel')
                        ->color('danger')
                        ->icon('heroicon-o-no-symbol')      
                        ->requiresConfirmation()                  
                        ->action(fn(Preorder $record) => $record->find($record->id)->update(['status' => 'Cancel'])),
                    Tables\Actions\DeleteAction::make(),
                        // ->hidden(fn(Preorder $record) => $record->status != 'Baru' || auth()->user()->roles->pluck('name')[0] === 'customer_support'),
                ])
                
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
            'index' => Pages\ListPreorders::route('/'),
            'create' => Pages\CreatePreorder::route('/create'),
            'edit' => Pages\EditPreorder::route('/{record}/edit'),
        ];
    }
}
