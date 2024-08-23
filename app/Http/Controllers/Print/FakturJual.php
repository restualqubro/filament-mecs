<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\Jual;
use App\Models\Transaksi\DetailJual;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FakturJual extends Controller
{
    public function print($id, GeneralSettings $settings) 
    {
        $data = [
            'title'     => 'PDF View',
            'jual'      => Jual::where('id', $id)->get(),
            'items'     => DetailJual::where('jual_id', $id)->get(),
            'logo'      => Storage::url($settings->brand_logo),
            'site'      => $settings->brand_name,
        //     // 'items'     => LayananCuti::where('surat_id', $id)->get(),
            // 'image'     => base64_encode(QrCode::size(100)->generate(url('/validate/cuti/'.$id)))

        ];  
        // return "adasdas"  	;
    	return View('print.fakturjual', $data);
    }
}
