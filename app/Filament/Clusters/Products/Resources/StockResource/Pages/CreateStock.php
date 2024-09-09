<?php

namespace App\Filament\Clusters\Products\Resources\StockResource\Pages;

use App\Filament\Clusters\Products\Resources\StockResource;
use Filament\Actions;
use App\Models\Products\Stock;
use Filament\Resources\Pages\CreateRecord;

class CreateStock extends CreateRecord
{
    protected static string $resource = StockResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $stock = Stock::where('product_id', $data['product_id'])->max('code');
        if ($stock != null) 
        {                                                                                            
            $tmp = substr($stock, 1, 3)+1;
            $code = sprintf("%03s", $tmp);                                                                            
        } else {
            $code =  "001";
        }

        $data['code'] = $code;

        return $data;
    }
}
