<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Keuangan extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $pluralModelLabel = 'Pemasukan & Pengeluaran';
}
