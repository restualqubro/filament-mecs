<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\BankTransferResource\Pages;
use App\Filament\Resources\Finance\BankTransferResource\RelationManagers;
use App\Models\Finance\BankTransfer;
use App\Models\Finance\BankAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankTransferResource extends Resource
{
    protected static ?string $model = BankTransfer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $navigationGroup = 'Finance';

    protected static ?string $pluralModelLabel = 'Bank Transfer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nominal') 
                    ->label('Nominal Transfer')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label('Tipe')
                    ->required()
                    ->options([
                        'masuk' => 'Masuk',
                        'keluar'=> 'Keluar',
                    ]),
                Forms\Components\Select::make('account_id')
                    ->label('Bank Account')
                    ->options(BankAccount::all()->pluck('bank_name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('account.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'success'   => 'masuk',
                        'danger'    => 'keluar'
                    ]),
                Tables\Columns\TextColumn::make('nominal')
                    ->numeric(decimalPlaces:0),                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListBankTransfers::route('/'),
            // 'create' => Pages\CreateBankTransfer::route('/create'),
            // 'edit' => Pages\EditBankTransfer::route('/{record}/edit'),
        ];
    }
}
