<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Models\FinalTripalStock;

class FinalTripalStockController extends Controller
{
    public function index()
    {
       $helper = new AppHelper();
       $settings = $helper->getGeneralSettigns();
       $finaltripal = FinalTripalStock::paginate(50);

        return view('admin.tripal_stock.finaltripalstock.index',
        compact('settings','finaltripal'));
    }
}
