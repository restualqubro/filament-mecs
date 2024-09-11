<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\Preorder;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FakturPreorder extends Controller
{
    public function print($id, GeneralSettings $settings) 
    {
        $data = [
            'title'     => 'Preorder',
            'items'     => Preorder::find($id),
            'logo'      => Storage::url($settings->brand_logo),
            'site'      => $settings->brand_name,
        ];                
    	return View('print.fakturpreorder', $data);
    }
}
