<?php

namespace App\Filament\Clusters\Products\Resources\StockResource\Pages;

use App\Filament\Clusters\Products\Resources\StockResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStock extends CreateRecord
{
    protected static string $resource = StockResource::class;
}
