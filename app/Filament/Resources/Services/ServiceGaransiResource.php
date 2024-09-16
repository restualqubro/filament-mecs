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
use Carbon\Carbon;

class ServiceGaransiResource extends Resource
{
    protected static ?string $model = Garansi::class;

    protected static ?string $navigationIcon = 'heroicon-o-backward';

    protected static ?string $navigationGroup = 'Service';

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
                Tables\Columns\TextColumn::make('created_at'),
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
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('status_edit')
                        ->label('Update')
                        ->color('success')
                        ->icon('heroicon-o-document-check')
                        ->form([
                            Forms\Components\TextArea::make('description')                                                                     
                                ->label('Update Details')
                                                            
                        ])
                        ->action(function (array $data, Garansi $row): void {
                            $record[] = array();
                            $record['service_id'] = $row->id;
                            $record['user_id'] = auth()->user()->id;
                            $record['description'] = $data['description'];
                            $record['status'] = 'Proses';                        
                            LogService::create($record);
                            Garansi::where('id', $row->id)->update(['status' => 'Proses']);                        
                        })->hidden(fn(Garansi $record) => $record->status != 'Baru' || $record->status != 'Proses' || auth()->user()->roles->pluck('name')[0] === 'customer_support'),
                ])
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
