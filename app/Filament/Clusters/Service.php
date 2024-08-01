<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Service extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Categories & Catalogue';

    protected static ?string $navigationGroup = 'Service';
}
