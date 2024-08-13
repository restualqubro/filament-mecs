<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\ServiceDataResource\Pages;
use App\Models\Service\Data;
use App\Models\Service\Cancel;
use App\Models\Service\LogService;
use App\Models\Connect\Customers;
use App\Models\Service\Categories;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Carbon\Carbon;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceDataResource extends Resource
{
    protected static ?string $model = Data::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Service';

    protected static ?string $slug = 'service-data';

    protected static ?string $pluralModelLabel = 'Service Data';
    

    public static function form(Form $form): Form
    {        
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date_in')
                    ->label('Tanggal Masuk')
                    ->required()
                    ->default('now'),
                Forms\Components\Select::make('customer_id')
                    ->label('Customer')
                    ->required()
                    ->options(Customers::all()->pluck('name', 'id'))
                    ->searchable()
                    ->reactive(),
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->required()
                    ->options(Categories::all()->pluck('name', 'id'))
                    ->searchable()
                    ->reactive(),
                Forms\Components\TextInput::make('merk')
                    ->label('Merk')
                    ->required(),
                Forms\Components\TextInput::make('seri')
                    ->label('Seri / Tipe')
                    ->required(),          
                Forms\Components\TextInput::make('sn')          
                    ->label('Serial Number'),
                Forms\Components\TextInput::make('kelengkapan')
                    ->label('Kelengkapan')
                    ->required()
                    ->columnSpan(2),
                Forms\Components\Textarea::make('keluhan')
                    ->label('Keluhan')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Keterangan'),  
                Forms\Components\Hidden::make('status')
                    ->default('Baru'),
                Forms\Components\Hidden::make('code')
                    ->default(function () {
                        $last = Data::whereMonth('created_at', '=', date('m'))
                        ->whereYear('created_at', '=', date('Y'))
                        ->max('code');                          
                        // $code = '';
                        if ($last != null) {                                                                                            
                            $tmp = substr($last, 6, 3)+1;
                            return "SV".Carbon::now()->format('my').sprintf("%03s", $tmp);                                                                            
                        } else {
                            return "SV".Carbon::now()->format('my')."001";
                        }           
                        // $code; 
                    })
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_in')
                    ->label('Tanggal Masuk')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('merk')
                    ->label('Brand/Merk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('seri')
                    ->label('Seri/Tipe')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Baru' => 'gray',
                        'Proses' => 'warning',
                        'Selesai' => 'success',
                        'Cancel' => 'danger',
                        'Keluar' => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('contact')
                    ->hiddenLabel()
                    ->tooltip('Contact')
                    ->url(function($record) {
                        
                        // return dd($record);
                        return 'https://wa.me/+62'.$record->customer->telp."?text=Assalamu'alaikum,%20Salam%20Kami%20dari%20Mecs%20Komputer%20Ingin%20Mengupdate%20Unit%20dengan%20kode%20".$record->code."%20atas%20nama%20".$record->customer->name;
                    })
                    ->color('success')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('print')
                    ->hiddenLabel()
                    ->tooltip('Print')
                    ->url(function($record) {
                        
                        // return dd($record);
                        return 'https://wa.me/+62'.$record->customer->telp."?text=Assalamu'alaikum,%20Salam%20Kami%20dari%20Mecs%20Komputer%20Ingin%20Mengupdate%20Unit%20dengan%20kode%20".$record->code."%20atas%20nama%20".$record->customer->name;
                    })
                    ->color('warning')
                    ->icon('heroicon-o-printer')                    
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make()->hiddenLabel()->tooltip('Details'),
                Tables\Actions\EditAction::make()->hiddenLabel()->tooltip('Edit'),
                Tables\Actions\Action::make('status_edit')
                    ->hiddenLabel()
                    ->tooltip('Edit Status')
                    ->color('success')
                    ->icon('heroicon-o-document-check')
                    ->form([
                        Forms\Components\TextArea::make('description')                                                                     
                            ->label('Update Details')
                                                        
                    ])
                    ->action(function (array $data, Data $row): void {
                        $record[] = array();
                        $record['service_id'] = $row->id;
                        $record['user_id'] = auth()->user()->id;
                        $record['description'] = $data['description'];
                        $record['status'] = 'Proses';                        
                        LogService::create($record);
                        Data::where('id', $row->id)->update(['status' => 'Proses']);
                    }),
                Tables\Actions\Action::make('cancel')
                    ->hiddenLabel()
                    ->tooltip('Cancel Service')
                    ->color('danger')
                    ->icon('heroicon-o-no-symbol')
                    ->form([
                        Forms\Components\TextArea::make('description')                                                                     
                            ->label('Cancel Details'),
                        Forms\Components\Select::make('teknisi_id')
                            ->label('Teknisi Bersangkutan')
                            ->options(                                                
                                User::get()->mapWithKeys(function (User $user) {
                                    return [$user->id => sprintf('%s %s', $user->firstname, $user->lastname)];
                                })
                                )
                                                        
                    ])
                    ->action(function (array $data, Data $row): void {
                        $record[] = array();
                        $record['service_id'] = $row->id;
                        $record['user_id'] = auth()->user()->id;
                        $record['teknisi_id'] = auth()->user()->id;
                        $record['alasan'] = $data['description'];
                        $record['description'] = $data['description'];
                        $record['status'] = 'Cancel';                        
                        LogService::create($record);
                        Cancel::create($record);
                        Data::where('id', $row->id)->update(['status' => 'Cancel']);
                    })
            ])
            ->bulkActions([
                
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([                
                TextEntry::make('code')
                    ->label('Kode Service')
                    ->weight(FontWeight::Bold),
                TextEntry::make('customer.name')
                    ->label('Nama Customer'),
                TextEntry::make('date_in')
                    ->label('Tanggal Masuk'),
                TextEntry::make('category.name')
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
                TextEntry::make('penawaran')
                    ->label('Penawaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Setuju' => 'success',
                        'Tidak' => 'warning',                        
                    }),
                TextEntry::make('merk')
                    ->label('Merk/Brand'),
                TextEntry::make('seri')
                    ->label('Seti/Tipe'),
                TextEntry::make('sn')
                    ->label('Serial Number/Model'),                                
                TextEntry::make('kelengkapan')
                    ->label('Kelengkapan'),
                TextEntry::make('keluhan')
                    ->label('Keluhan'),
                TextEntry::make('description')
                    ->label('Keterangan/Update'),                                    
                RepeatableEntry::make('logservice')
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
            'index' => Pages\ListServiceData::route('/'),            
            // 'view'  => Pages\ViewServiceData::route('/{record}')
            // 'create' => Pages\CreateServiceData::route('/create'),
            // 'edit' => Pages\EditServiceData::route('/{record}/edit'),
        ];
    }
}
