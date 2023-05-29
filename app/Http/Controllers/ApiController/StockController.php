<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    //
    public function index()
    {
        $stocks = Stock::with('category', 'item', 'department')->get();
        return $stocks;
        // return view('admin.Stock.itemStock', compact('stocks'));
    }
}
