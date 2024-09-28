<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\ServiceGaransiResource\Pages;
use App\Filament\Resources\Services\ServiceGaransiResource\RelationManagers;
use App\Models\Service\Garansi;
use App\Models\Service\LogService;
use App\Models\Service\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Carbon\Carbon;
use Filament\Support\Enums\FontWeight;

class ServiceGaransiResource extends Resource
{
    protected static ?string $model = Garansi::class;

    protected static ?string $navigationIcon = 'heroicon-o-backward';

    protected static ?string $navigationGroup = 'Service';

    protected static ?string $pluralModelLabel = 'Service Garansi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()                    
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Faktur Garansi')
                            ->default(function() {
                                $date = Carbon::now()->format('my');
                                $last = Invoice::whereRaw("MID(code, 5, 4) = $date")->max('code');                                        
                                if ($last != null) {                                                                                            
                                    $tmp = substr($last, 8, 4)+1;
                                    return "FKG-".$date.sprintf("%03s", $tmp);                                                                            
                                } else {
                                    return "FKG-".$date."001";
                                }
                            })
                            ->readonly()
                            ->required(),                   
                        Forms\Components\DateTimePicker::make('dateTime')
                            ->default(now())
                            ->disabled(),
                        Forms\Components\Select::make('invoice_id')
                            ->label('Faktur Invoice')
                            ->options(Invoice::all()->pluck('code', 'id'))
                            ->required()
                            ->live()
                            ->afterStateUpdated(function($state, Forms\Get $get, Forms\Set $set) {
                                $invoice = Invoice::find($state);
                                if ($invoice) 
                                {
                                    $set('name', $invoice->selesai->service->customer->name);
                                    $set('merk', $invoice->selesai->service->merk);
                                    $set('seri', $invoice->selesai->service->seri);
                                }
                            }),
                        Forms\Components\TextInput::make('name')
                            ->disabled(),
                        Forms\Components\TextInput::make('merk')
                            ->disabled(),
                        Forms\Components\TextInput::make('seri')
                            ->disabled()                        
                    ])->columns(3),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('kelengkapan')
                            ->required(),                        
                        Forms\Components\Textarea::make('keluhan')
                            ->required(),                        
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice.selesai.service.customer.name')
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('invoice.selesai.service.merk')                    
                    ->label('Merk'),
                Tables\Columns\TextColumn::make('invoice.selesai.service.seri'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('last updated'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Baru' => 'gray',
                        'Proses' => 'warning',
                        'Selesai' => 'success',
                        'Cancel' => 'danger',
                        'Keluar' => 'gray',
                        
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([                    
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('status_edit')
                        ->label('Update')
                        ->color('success')
                        ->icon('heroicon-o-document-check')
                        ->form([
                            Forms\Components\Select::make('status')                                                                     
                                ->label('Status')
                                ->required()
                                ->options([
                                    'Proses'    => 'Proses',
                                    'Cancel'    => 'Cancel',
                                    'Selesai'   => 'Selesai',
                                    'Keluar'    => 'Keluar',
                                ]),
                            Forms\Components\Textarea::make('description')                                                                     
                                ->label('Update Details')
                                ->required()                                                                                          
                        ])
                        ->modalWidth('xl')
                        ->action(function (array $data, Garansi $row): void {
                            $record[] = array();
                            $record['service_id'] = $row->invoice->selesai->service->id;
                            $record['user_id'] = auth()->user()->id;
                            $record['description'] = 'Garansi : '.$data['description'];
                            $record['status'] = $data['status'];                                             
                            // dd($record);
                            LogService::create($record);
                            Garansi::where('id', $row->id)->update(['status' => $record['status'], 'update' => $record['description']]);                        
                        }),
                    Tables\Actions\DeleteAction::make(),
                ])
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }  
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([     
                Section::make('Service Details')
                    ->schema([
                        TextEntry::make('code')
                            ->label('Kode Faktur Garansi')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('invoice.selesai.service.code')
                            ->label('Kode Service')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('invoice.selesai.service.customer.name')
                            ->label('Nama Customer'),
                        TextEntry::make('created_at')
                            ->label('Tanggal Masuk'),
                        TextEntry::make('invoice.selesai.service.category.name')
                            ->label('Kategori'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Baru' => 'gray',
                                'Proses' => 'warning',
                                'Selesai' => 'success',
                                'Cancel' => 'danger',
                                'Keluar' => 'gray',
                                
                            }),                        
                        TextEntry::make('invoice.selesai.service.merk')
                            ->label('Merk/Brand'),
                        TextEntry::make('invoice.selesai.service.seri')
                            ->label('Seti/Tipe'),                        
                        TextEntry::make('kelengkapan')
                            ->label('Kelengkapan'),
                        TextEntry::make('keluhan')
                            ->label('Keluhan'),
                        TextEntry::make('update')
                            ->label('Keterangan/Update'),
                ])->columns(2),                                                             
                RepeatableEntry::make('invoice.selesai.service.logservice')
                    ->label('History')
                    ->schema([
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Baru' => 'gray',
                                'Proses' => 'warning',
                                'Selesai' => 'success',
                                'Cancel' => 'danger',
                                'Keluar' => 'gray',
                                'Kembali' => 'warning'
                            }),     
                        TextEntry::make('created_at')
                            ->label('DateTime')
                            ->dateTime(),                                                                                                   
                        TextEntry::make('description')
                            ->label('Details'),
                        TextEntry::make('user.name')
                            ->label('Petugas')
                    ])->columns(2)
                    ->columnSpan(2)                                                                        
                                    
            ])->columns(2);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceGaransis::route('/'),
            'create' => Pages\CreateServiceGaransi::route('/create'),
            'edit' => Pages\EditServiceGaransi::route('/{record}/edit'),
        ];
    }
}
