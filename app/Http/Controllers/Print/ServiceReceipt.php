<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Models\Finance\BankAccount;
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
            'brand_name'=> $settings->brand_name,                    

        ];  
        return View('print.servicereceipt', $data);  	        
    }
}
