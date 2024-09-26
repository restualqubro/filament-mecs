<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Service\DetailService;
use App\Models\Service\DetailComponent;
use App\Models\Transaksi\DetailJual;
use App\Models\Transaksi\DetailBeli;
use App\Models\Finance\BankTransfer;
use App\Models\Finance\Penarikan;
use Illuminate\Support\Facades\DB;

class FinanceWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // get Data Omzet
        $getOmzetJual = DetailJual::whereHas('jual', function ($q) {
                            $q->where('is_pending', '=', 0);
                            $q->where('status', '!=', 'Piutang');                    
                        })->sum(DB::raw('qty * (hjual - disc)'));
        // get Data Profit 
        $getServiceSum = DetailService::whereHas('selesai', function($q) {
            $q->whereHas('invoice', function($q) {
                $q->where('status', '!=', 'Piutang');
            });
        })->sum(DB::raw('service_qty * (biaya - service_disc)'));

        $getComponentSum = DetailComponent::whereHas('selesai', function($q) {
            $q->whereHas('invoice', function($q) {
                $q->where('status', '!=', 'Piutang');
            });            
        })->sum(DB::raw('component_qty * hbeli'));
        
        $getProfitJual = DetailJual::whereHas('jual', function ($q) {
                            $q->where('is_pending', '=', 0);
                            $q->where('status', '!=', 'Piutang');
                            })->sum('profit');

        // get Data Utang - Piutang
        $getPiutangService = DetailService::whereHas('selesai', function($q) {
            $q->whereHas('invoice', function($q) {
                $q->where('status', '=', 'Piutang');
            });
        })->sum(DB::raw('service_qty * (biaya - service_disc)'));

        $getPiutangPenjualan = DetailJual::whereHas('jual', function ($q) {
                                $q->where('is_pending', '=', 0);
                                $q->where('status', '=', 'Piutang');                    
                            })->sum(DB::raw('qty * (hjual - disc)')); 

        $getUtangPembelian = DetailBeli::whereHas('beli', function ($q) {            
                                $q->where('status', '=', 'Utang');                    
                            })->sum(DB::raw('qty * hbeli')); 

        // getSaldo
        $getTransferSum = BankTransfer::all()->sum('nominal');                
        $getPenarikanCash = Penarikan::where('sumber', 'Cash')->sum('nominal');
        $getPenarikanRekening = Penarikan::where('sumber', 'Rekening')->sum('nominal');

        return [
            Stat::make('Saldo Cash', number_format(($getOmzetJual + $getServiceSum - $getTransferSum - $getPenarikanCash), 0, '', '.')),
            Stat::make('Saldo Mandiri', number_format($getTransferSum - $getPenarikanRekening, 0, '', '.')),            
            Stat::make('Penarikan Tunai', number_format(($getPenarikanCash + $getPenarikanRekening), 0, '', '.')),
            Stat::make('Omzet Service', number_format($getServiceSum, 0, '', '.')),
            Stat::make('Omzet Penjualan', number_format($getOmzetJual, 0, '', '.')),            
            Stat::make('Total Omzet', number_format(($getOmzetJual + $getServiceSum), 0, '', '.')),
            Stat::make('Profit Service', number_format(($getServiceSum - $getComponentSum) , 0, '', '.')),
            Stat::make('Profit Penjualan',  number_format($getProfitJual, 0, '', '.')),
            Stat::make('Total Profit',  number_format($getProfitJual + ($getServiceSum - $getComponentSum), 0, '', '.')),
            Stat::make('Piutang Service', number_format($getPiutangService, 0, '', '.')),
            Stat::make('Piutang Penjualan', number_format($getPiutangPenjualan, 0, '', '.')),
            Stat::make('Utang Pembelian', number_format($getUtangPembelian, 0, '', '.')),
        ];
    }
}
