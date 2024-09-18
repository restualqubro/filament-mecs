<?php

namespace App\Filament\Clusters\Peminjaman\Resources;

use App\Filament\Clusters\Peminjaman;
use App\Filament\Clusters\Peminjaman\Resources\PeminjamanPartResource\Pages;
use App\Filament\Clusters\Peminjaman\Resources\PeminjamanPartResource\RelationManagers;
use App\Models\Products\PeminjamanPart;
use App\Models\Products\Stock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PeminjamanPartResource extends Resource
{
    protected static ?string $model = PeminjamanPart::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Peminjaman::class;

    public static function form(Form $form): Form
    {
        $stock = Stock::get();

        return $form
            ->schema([
                Forms\Components\Select::make('stock_id')
                    ->label('Kode Stock')                                                                                        
                    ->options(                                                
                        $stock->mapWithKeys(function (Stock $stock) {
                            return [$stock->id => sprintf('%s-%s | %s', $stock->product->code, $stock->code, $stock->product->name)];
                        })
                        )                                                                                            
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('qty'),
                Forms\Components\Textarea::make('description')            
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([                
                Tables\Columns\TextColumn::make('stock_id'),
                Tables\Columns\TextColumn::make('stock.product.name'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Submitted By')   
                    ->badge()
                    ->colors([
                        'success'   => 'Approved',
                        'danger'    => 'Reject',
                        'gray'      => 'Baru'
                    ]),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Aprrove')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')                        
                        ->requiresConfirmation()
                        ->modalHeading('Approve')
                        ->color('success')
                        ->modalDescription('Are you sure you\'d like to approve this ?')
                        ->modalSubmitActionLabel('Yes, approve it')
                        ->action(fn (PeminjamanPart $record) => $record->update([
                            'status'        => 'Approve',
                            'approval_id'   => auth()->id()
                        ]))
                        ->hidden(fn(PeminjamanPart $record)=> $record->status != 'Baru' || auth()->user()->roles->pluck('name')[0] != 'super_admin'),
                    Tables\Actions\Action::make('Reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->modalHeading('Reject')
                        ->modalDescription('Are you sure you\'d like to reject this ?')
                        ->modalSubmitActionLabel('Yes, reject it')
                        ->action(fn (PeminjamanPart $record) => $record->update([
                            'status'        => 'Reject',
                            'approval_id'   => auth()->id()
                        ]))
                        ->hidden(fn(PeminjamanPart $record)=> $record->status != 'Baru' || auth()->user()->roles->pluck('name')[0] != 'super_admin'),
                    Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPeminjamanParts::route('/'),
            // 'create' => Pages\CreatePeminjamanPart::route('/create'),
            // 'edit' => Pages\EditPeminjamanPart::route('/{record}/edit'),
        ];
    }
}
