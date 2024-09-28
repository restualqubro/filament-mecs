<?php

namespace App\Http\Controllers\print;

use App\Http\Controllers\Controller;
use App\Settings\GeneralSettings;
use App\Models\Products\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StockMinus extends Controller
{
    public function print(GeneralSettings $settings) 
    {
        $categoryid = request('tableFilters.category_id.value');
        if (!$categoryid) 
        {
            $items = Stock::get();        
        }  else {
            $items = Stock::whereHas('product', fn($q) => $q->where('category_id', $categoryid))->get();
        }
        $data = [
            'title'     => 'STOK MINUS',
            'logo'      => Storage::url($settings->brand_logo),
            'item'      => $items,
            'dateTime'  => \Carbon\Carbon::now(),
            'users'     => auth()->user()->name,
        ];
    	return view('print.stockminus', $data);
    }
}
