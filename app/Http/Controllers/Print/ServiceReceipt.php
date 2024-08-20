<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service\Data;

class ServiceReceipt extends Controller
{
    public function print($id) 
    {
        $data = [
            'title'     => 'PDF View',
            'items'     => Data::where('id', $id)->get(),
        //     // 'items'     => LayananCuti::where('surat_id', $id)->get(),
            // 'image'     => base64_encode(QrCode::size(100)->generate(url('/validate/cuti/'.$id)))

        ];    	
    	return View('print.servicereceipt', $data);
    }
}
