<?php

namespace App\Filament\Clusters\Peminjaman\Resources\PengembalianPartResource\Pages;

use App\Filament\Clusters\Peminjaman\Resources\PengembalianPartResource;
use App\Models\Products\PeminjamanPart;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePengembalianPart extends CreateRecord
{
    protected static string $resource = PengembalianPartResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        PeminjamanPart::where('id', $data['peminjaman_id'])->update([
            'status'    => 'Kembali'
        ]);
        return $data;
    }
}
