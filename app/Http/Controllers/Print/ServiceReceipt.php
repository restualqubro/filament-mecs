<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Service\Data;
use App\Settings\GeneralSettings;

class ServiceReceipt extends Controller
{
    public function print($id, GeneralSettings $settings) 
    {
        $data = [
            'title'     => 'Tanda Terima Service',
            'items'     => Data::where('id', $id)->get(),
            'logo'      => Storage::url($settings->brand_logo)
        //     // 'items'     => LayananCuti::where('surat_id', $id)->get(),
            // 'image'     => base64_encode(QrCode::size(100)->generate(url('/validate/cuti/'.$id)))

        ];    	
    	return View('print.servicereceipt', $data);
    }
}
