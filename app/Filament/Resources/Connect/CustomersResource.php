<?php

namespace App\Filament\Resources\Connect;

use App\Filament\Resources\Connect\CustomersResource\Pages;
use App\Filament\Resources\Connect\CustomersResource\RelationManagers;
use App\Models\Connect\Customers;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CustomersResource extends Resource
{
    protected static ?string $model = Customers::class;

    protected static ?string $navigationGroup = 'Connect';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('code')
                    ->required()
                    ->default(function () {                            
                            $date = Carbon::now()->format('my');                            
                            $record = Customers::latest('code')->whereRaw("MID(code, 4, 4) = ".$date)->first();
                            // $record = DB::table('customers')
                            //             ->select('*')
                            //             ->get();                                        
                            if ($record === null) {
                                return "CST".$date."001";                                
                            } else {
                                $tmp = Str::substr($record->code, 7, 3)+1;
                                $code = sprintf("%03s", $tmp);
                                return "CST".$date.$code;
                            }
                        }
                    ),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required(),
                Forms\Components\TextInput::make('telp')
                    ->label('No HP/WA')
                    ->tel()
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                    ->prefix('+62')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label('Tipe Customer')
                    ->options([
                        'Customer'  => 'Customer',
                        'Reseller'  => 'Reseller'
                    ])
                    ->required(),
                Forms\Components\TextArea::make('address')                    
                    ->label('Alamat')    
                    ->required()                  
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Code Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis Customer')                    
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hiddenLabel()->tooltip('Edit'),
                Tables\Actions\DeleteAction::make()->hiddenLabel()->tooltip('Delete'),
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
            'index' => Pages\ListCustomers::route('/'),
            // 'create' => Pages\CreateCustomers::route('/create'),
            // 'edit' => Pages\EditCustomers::route('/{record}/edit'),
        ];
    }
}
