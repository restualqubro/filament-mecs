<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Keuangan extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Cash In & Out';
}
