<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Models\Service\DetailService;
use App\Models\Service\Invoice;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceReceipt extends Controller
{
    public function print($id, GeneralSettings $settings) 
    {
        $items = Invoice::where('id', $id)->first();
        $data = [
            'title'     => 'Invoice Service Receipt',
            'items'     => $items,
            'data'      => DetailService::where('selesai_id', $items->selesai_id)->get(),
            'logo'      => Storage::url($settings->brand_logo),            
        //     // 'items'     => LayananCuti::where('surat_id', $id)->get(),
            // 'image'     => base64_encode(QrCode::size(100)->generate(url('/validate/cuti/'.$id)))

        ];    	
    	return View('print.invoicereceipt', $data);
    }
}
