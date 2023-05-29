<?php

namespace App\Http\Controllers;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseReport extends Controller
{
    // return purchase report page
    public function purchaseReport()
    {
        $suppliers = Supplier::where('status', 1)->latest()->get();
        return view('admin.reports.purchases.index', compact('suppliers'));
    }


    // filter purchase report
    public function postPurchaseReport(Request $request)
    {

        $validator = $request->validate([
            'fromDate' => 'required|date|before:toDate',
            'toDate' => 'required|date|after:fromDate',
        ]);

        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $supplier = $request->supplier;


        if(!empty($fromDate) && !empty($toDate) && !empty($supplier))
        {
            $purchases = Purchase::where('supplier_id', $supplier)->whereBetween('purchase_date', [$fromDate, $toDate])->latest()->paginate(15);
        }
        else
        {
            $purchases = Purchase::whereBetween('purchase_date', [$fromDate, $toDate])->latest()->paginate(15);
        }
        $filters = $request;
        $suppliers = Supplier::where('status', 1)->latest()->get();
        return view('admin.reports.purchases.index', compact('suppliers', 'purchases', 'filters'));
    }
}
