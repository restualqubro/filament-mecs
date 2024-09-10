<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\ServiceTopartnerResource\Pages;
use App\Filament\Resources\Services\ServiceTopartnerResource\RelationManagers;
use App\Models\Service\ToPartner;
use App\Models\Service\Data;
use App\Models\Connect\Partner;
use App\Models\Service\LogService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Carbon\Carbon;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceTopartnerResource extends Resource
{
    protected static ?string $model = Topartner::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Service';

    protected static ?string $pluralModelLabel = 'Service to Partner';

    protected static ?string $slug = 'service-topartner';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_id')
                    ->label('Kode Service')
                    ->searchable()
                    ->reactive()
                    ->options(Data::all()->where('status', 'Proses')->pluck('code', 'id')),
                Forms\Components\Select::make('partner_id')
                    ->label('Partner')
                    ->searchable()
                    ->reactive()
                    ->options(Partner::all()->pluck('name', 'id')),
                Forms\Components\DatePicker::make('date_send')                    
                    ->label('Tanggal Pengiriman')
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('service.code')
                    ->label('Kode Service'),
                Tables\Columns\TextColumn::make('service.customer.name')[0][0][0]
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('service.merk') 
                    ->label('Merk / Brand'),
                Tables\Columns\TextColumn::make('service.seri')
                    ->label('Seri / Tipe'),
                Tables\Columns\TextColumn::make('partner.name')[0][0][0],
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Kirim' => 'gray',
                        'Proses' => 'warning',
                        'Selesai' => 'success',
                        'Cancel' => 'danger',
                        'Kembali' => 'gray',
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->visible(fn($record): string => ($record->status === 'BARU' || $record->status === 'Proses')),
                    Tables\Actions\Action::make('status_edit')
                        ->label('Update')
                        ->color('secondary')
                        ->icon('heroicon-o-document-check')
                        ->form([
                            Forms\Components\TextArea::make('description')                                                                     
                                ->label('Update Details')
                                                            
                        ])
                        ->action(function (array $data, ToPartner $row): void {
                            $record[] = array();
                            $record['service_id'] = $row->service_id;
                            $record['user_id'] = auth()->user()->id;
                            $record['description'] = $data['description'];                                  
                            LogService::create($record);
                            ToPartner::where('id', $row->id)->update([
                                'update'   => 'inPartner : '.$data['description'],
                                'status'   => 'Proses'              

                            ]);
                        })
                        ->modalWidth('md')
                        ->visible(fn($record): string => ($record->status === 'BARU' || $record->status === 'Proses')),
                    Tables\Actions\Action::make('selesai') 
                        ->label('Selesai')                     
                        ->color('success')
                        ->icon('heroicon-o-check')
                        ->form([
                            Forms\Components\TextArea::make('description')                                                                     
                                ->label('Update Details'),
                            Forms\Components\TextInput::make('biaya')                                                        
                                ->label('Biaya')
                                ->numeric()
                        ])
                        ->action(function (array $data, ToPartner $row): void {
                            $record[] = array();
                            $record['service_id'] = $row->service_id;
                            $record['user_id'] = auth()->user()->id;
                            $record['description'] = $data['description']; 
                            $record['status'] = 'Selesai';
                            LogService::create($record);
                            ToPartner::where('id', $row->id)->update([
                                'update'   => 'inPartner : '.$data['description'],
                                'biaya'     => $data['biaya'],
                                'status'   => 'Selesai'              

                            ]);
                        })
                        ->modalWidth('md')
                        ->visible(fn($record): string => ($record->status === 'BARU' || $record->status === 'Proses')),
                    Tables\Actions\Action::make('cancel') 
                        ->label('Cancel')                       
                        ->color('danger')
                        ->icon('heroicon-o-no-symbol')
                        ->form([
                            Forms\Components\TextArea::make('description')                                                                     
                                ->label('Update Details'),                        
                        ])
                        ->action(function (array $data, ToPartner $row): void {
                            $record[] = array();
                            $record['service_id'] = $row->service_id;
                            $record['user_id'] = auth()->user()->id;
                            $record['description'] = $data['description']; 
                            $record['status'] = 'Cancel';
                            LogService::create($record);
                            ToPartner::where('id', $row->id)->update([
                                'update'   => 'inPartner : '.$data['description'],
                                'status'   => 'Cancel'              

                            ]);
                        })
                        ->modalWidth('md')
                        ->visible(fn($record): string => ($record->status === 'BARU' || $record->status === 'Proses')),
                    Tables\Actions\Action::make('ambil')
                        ->label('Pengambilan')
                        ->color('info')
                        ->icon('heroicon-o-truck')
                        ->form([
                            Forms\Components\TextArea::make('description')                                                                     
                                ->label('Update Details'),                        
                            Forms\Components\TextInput::make('biaya')
                                ->label('Biaya') 
                                ->default(fn($record): string => ($record->biaya))                           
                                ->disabled(),
                            Forms\Components\TextInput::make('bayar')
                                ->label('Bayar')
                                ->afterStateUpdated(function(Forms\Get $get, Forms\Set $set) {
                                    $biaya = $get('biaya');
                                    $bayar = $get('bayar');
                                    $sisa = $biaya - $bayar;
                                    $set('sisa', $sisa);
                                    if ($sisa > 0) {
                                        $set('status_pembayaran', 'Belum Lunas');
                                    } else {
                                        $set('status_pembayaran', 'Lunas');
                                    }
                                })
                                ->live(),
                            Forms\Components\TextInput::make('sisa')
                                ->label('Sisa Pembayaran'),
                            Forms\Components\Hidden::make('status_pembayaran')
                        ])
                        ->action(function (array $data, ToPartner $row): void {
                            $record[] = array();
                            $record['service_id'] = $row->service_id;
                            $record['user_id'] = auth()->user()->id;
                            $record['description'] = $data['description']; 
                            $record['status'] = 'Cancel';
                            LogService::create($record);
                            ToPartner::where('id', $row->id)->update([
                                'update'   => 'inPartner : '.$data['description'],
                                'status_pembayaran' => $data['status_pembayaran'],
                                'status'   => 'Diambil'              

                            ]);
                        })
                        ->modalWidth('md')
                        ->hidden(fn($record): string => ($record->status === 'BARU' || $record->status === 'Proses' || $record->status === 'Kembali'))
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
                TextEntry::make('service.code')
                    ->label('Kode Service')
                    ->weight(FontWeight::Bold),
                TextEntry::make('service.customer.name')[0][0][0]
                    ->label('Nama Customer'),
                TextEntry::make('service.merk')
                    ->label('Merk/Brand'),
                TextEntry::make('service.seri')
                    ->label('Seti/Tipe'),      
                TextEntry::make('date_send')
                    ->label('Tanggal Dikirim'),                
                TextEntry::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Baru' => 'gray',
                        'Proses' => 'warning',
                        'Selesai' => 'success',
                        'Cancel' => 'danger',
                        'Kembali' => 'gray',
                    }),
                TextEntry::make('biaya')
                    ->label('Biaya')
                    ->money('IDR'),
                TextEntry::make('status_pembayaran')
                    ->label('Status Pembayaran')                    
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {                    
                        'Lunas' => 'success',
                        'Belum Lunas' => 'warning',                        
                    }),                          
            ])->columns(2);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceTopartners::route('/'),
            // 'create' => Pages\CreateServiceTopartner::route('/create'),
            // 'edit' => Pages\EditServiceTopartner::route('/{record}/edit'),
        ];
    }
}
