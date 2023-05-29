<?php

namespace App\Http\Controllers;

use App\Models\FinishedProduct;
use App\Models\ProcessingProduct;
use App\Models\Purchase;
use App\Models\TransferredProduct;
use DB;
use Illuminate\Http\Request;

class ProductReport extends Controller
{
    // return processing product report page
    public function processingReport()
    {
        $purchases = Purchase::where('status', 1)->latest()->get();
        return view('admin.reports.products.processing', compact('purchases'));
    }

    // filter processing product for selected date
    public function filterProcessingReport(Request $request)
    {
        $validator = $request->validate([
            'fromDate' => 'required|date|before:toDate',
            'toDate' => 'required|date|after:fromDate',
        ]);
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $purchase = $request->purchase;
        $steps = DB::table('processing_product_staff')->select('processing_step_id')->distinct()->join('processing_steps', 'processing_steps.id', '=', 'processing_step_id')->select('processing_product_staff.processing_step_id as selected_step_id', 'processing_steps.name as pivot_step_name', 'processing_steps.id as pivot_step_id')->select('processing_steps.name as step_name', 'processing_steps.id as step_id')->get();
        if (!empty($fromDate) && !empty($toDate) && !empty($purchase)) {
            $processingProducts = ProcessingProduct::where('purchase_id', $purchase)->whereBetween('start_date', [$fromDate, $toDate])->latest()->paginate(15);
        } else {
            $processingProducts = ProcessingProduct::whereBetween('start_date', [$fromDate, $toDate])->latest()->paginate(15);
        }
        $filters = $request;
        $purchases = Purchase::where('status', 1)->latest()->get();
        return view('admin.reports.products.processing', compact('processingProducts', 'purchases', 'filters', 'steps'));
    }

    // return finished product report page
    public function finishedReport()
    {
        $processingProducts = ProcessingProduct::where('status', 1)->latest()->get();
        return view('admin.reports.products.finished', compact('processingProducts'));
    }

    // filter finished product for selected date
    public function filterFinishedReport(Request $request)
    {
        $validator = $request->validate([
            'fromDate' => 'required|date|before:toDate',
            'toDate' => 'required|date|after:fromDate',
        ]);

        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $processingPro = $request->processingPro;

        if (!empty($fromDate) && !empty($toDate) && !empty($processingPro)) {
            $finishedProducts = FinishedProduct::where('porcessing_pro_id', $processingPro)->whereBetween('finished_date', [$fromDate, $toDate])->latest()->paginate(15);
        } else {
            $finishedProducts = FinishedProduct::whereBetween('finished_date', [$fromDate, $toDate])->latest()->paginate(15);
        }
        $filters = $request;
        $processingProducts = ProcessingProduct::where('status', 1)->latest()->get();
        return view('admin.reports.products.finished', compact('finishedProducts', 'processingProducts', 'filters'));
    }


    // return transferred product report page
    public function transferredReport()
    {
        $finishedProducts = FinishedProduct::where('status', 1)->get();
        return view('admin.reports.products.transferred', compact('finishedProducts'));
    }

    // filter transferred product for selected date
    public function filterTransferredReport(Request $request)
    {
        $validator = $request->validate([
            'fromDate' => 'required|date|before:toDate',
            'toDate' => 'required|date|after:fromDate',
        ]);

        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $finishedPro = $request->finishedPro;


        if (!empty($fromDate) && !empty($toDate) && !empty($finishedPro)) {
            $transferredProducts = TransferredProduct::where('finished_id', $finishedPro)->whereBetween('transferred_date', [$fromDate, $toDate])->latest()->paginate(15);
        } else {
            $transferredProducts = TransferredProduct::whereBetween('transferred_date', [$fromDate, $toDate])->latest()->paginate(15);
        }
        $filters = $request;
        $finishedProducts = FinishedProduct::where('status', 1)->get();
        return view('admin.reports.products.transferred', compact('transferredProducts', 'finishedProducts', 'filters'));
    }
}
