<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Service\Data;
use App\Settings\GeneralSettings;
use Barryvdh\DomPDF\Facade\Pdf;

class ServiceReceipt extends Controller
{
    public function print($id, GeneralSettings $settings) 
    {
        $data = [
            'title'     => 'TANDA TERIMA SERVICE',
            'items'     => Data::find($id),
            'logo'      => Storage::url($settings->brand_logo),
            'name'      => auth()->user()->name,
            'brand_name'=> $settings->brand_name
        //     // 'items'     => LayananCuti::where('surat_id', $id)->get(),
            // 'image'     => base64_encode(QrCode::size(100)->generate(url('/validate/cuti/'.$id)))

        ];  
        return View('print.examplereceipt', $data);  	
        // return $pdf->stream();
    	// return View('print.servicereceipt', $data);
    }
}
