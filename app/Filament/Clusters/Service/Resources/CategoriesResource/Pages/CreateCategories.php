<?php

namespace App\Filament\Clusters\Service\Resources\CategoriesResource\Pages;

use App\Filament\Clusters\Service\Resources\CategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategories extends CreateRecord
{
    protected static string $resource = CategoriesResource::class;
}
