<?php

namespace App\Filament\Resources\Connect;

use App\Filament\Resources\Connect\EkspedisiResource\Pages;
use App\Filament\Resources\Connect\EkspedisiResource\RelationManagers;
use App\Models\Connect\Ekspedisi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;

class EkspedisiResource extends Resource
{
    protected static ?string $model = Ekspedisi::class;

    protected static ?string $navigationGroup = 'Connect';

    protected static ?string $pluralModelLabel = 'Ekspedisi';

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([                
                Forms\Components\TextInput::make('name')
                    ->label('Nama Ekspedisi')
                    ->required(),
                Forms\Components\TextInput::make('jenis')
                    ->label('Jenis Ekspedisi')
                    ->placeholder('Laut/Darat/Udara/Instan')                    
                    ->required(), 
                Forms\Components\SpatieMediaLibraryFileUpload::make('media')
                    ->label('Logo Ekspedisi')
                    ->image()
                    ->imageEditor()
                    ->collection('ekspedisi')                                        
                    ->required(),               
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([                
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Ekspedisi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis Ekspedisi'),
                SpatieMediaLibraryImageColumn::make('media')
                    ->collection('ekspedisi')                                                                                                 
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
            'index' => Pages\ListEkspedisis::route('/'),
            // 'create' => Pages\CreateEkspedisi::route('/create'),
            // 'edit' => Pages\EditEkspedisi::route('/{record}/edit'),
        ];
    }
}
