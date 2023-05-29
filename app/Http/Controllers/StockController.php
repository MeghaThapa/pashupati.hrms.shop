<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Department;
use App\Models\Size;
use App\Models\Stock;
use App\Models\Storein;
use App\Models\StoreinItem;
use App\Models\Unit;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stocks = Stock::with('category', 'item', 'department')->get();
        // return $stock;
        return view('admin.Stock.itemStock', compact('stocks'));
    }
    public function filterStockAccCategory($category_id)
    {
        $stock = Stock::with('item', 'department', 'category')->where('category_id', $category_id)->get();
        if ($stock) {
            return $stock;
        } else {
            // return "hello";
            return false;
        }
    }
    public function getDetailsAccItem($item_id){
        $stock=Stock::where('item_id', $item_id)->get()->first();
        return $stock;
    }

    public function departmentFilter()
    {
        $stocks = Stock::with('category', 'item', 'department')->get();
        $departments = Department::where('status', 'active')->get();
        return view('admin.Stock.departmentFilter', compact('stocks', 'departments'));
    }

    public function filterStock()
    {
        $categories = Category::where('status', '1')->get();
        // return $categories;
        $departments = Department::where('status', 'active')->get();
        // return $departments;
        $stocks = Stock::with('category', 'item', 'department')->get();
        return view('admin.Stock.filterStock', compact('stocks', 'categories', 'departments'));
    }

    public function filterStockAccDepartment($department_id)
    {
        $stock = Stock::with('item', 'department', 'category')->where('department_id', $department_id)->get();
        if ($stock) {
            return $stock;
        } else {
            // return "hello";
            return false;
        }
    }

    //update or create stock


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        //
    }
}
