<?php

namespace App\Filament\Resources\Connect\CustomersResource\Pages;

use App\Filament\Resources\Connect\CustomersResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomers extends CreateRecord
{
    protected static string $resource = CustomersResource::class;
}
