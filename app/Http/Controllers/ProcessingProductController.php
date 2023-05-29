<?php

namespace App\Http\Controllers;

use App\Models\ProcessingProduct;
use App\Models\ProcessingStep;
use App\Models\Purchase;
use App\Models\Staff;
use DB;
use Illuminate\Http\Request;
use PDF;

class ProcessingProductController extends Controller
{
    /**
     * Display a listing of the processing products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = ProcessingProduct::query();
        if (request('term')) {
            $term = request('term');
            $query->whereDate('start_date', $term)
                ->orWhereDate('end_date', 'Like', '%' . $term . '%')
                ->orWhere('processing_code', 'Like', '%' . $term . '%');
        }
        $processingProducts = $query->with('purchase')->orderBy('id', 'DESC')->paginate(15);
        $steps = DB::table('processing_product_staff')->select('processing_step_id')->distinct()->join('processing_steps', 'processing_steps.id', '=', 'processing_step_id')->select('processing_product_staff.processing_step_id as selected_step_id', 'processing_steps.name as pivot_step_name', 'processing_steps.id as pivot_step_id')->select('processing_steps.name as step_name', 'processing_steps.id as step_id')->get();
        return view('admin.processing.index', compact('steps', 'processingProducts'));
    }

    /**
     * Show the form for creating a new processing product.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $staff = Staff::where('status', 1)->latest()->get();
        $purchases = Purchase::where('status', 1)->latest()->get();
        $processingSteps = ProcessingStep::where('status', 1)->get();
        return view('admin.processing.create', compact('staff', 'purchases', 'processingSteps'));
    }

    /**
     * Store a newly created processing product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate form
        $validator = $request->validate([
            'purcahseProduct' => 'required|integer',
            "processingStaff*"  => "required|array|min:1",
            'startDate' => 'required|date|date_format:Y-m-d',
            'endDate' => 'nullable|date|date_format:Y-m-d|after:startDate',
            'note' => 'nullable|string|max:255',
            'attchedPic' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        // generate processing code
        $latestProcessing = ProcessingProduct::latest()->first();
        $processingCode = isset($latestProcessing->processing_code) ? $latestProcessing->processing_code + 1 :  1;

        // upload processing image
        $processingImage = '';
        if (isset($request->attchedPic)) {
            $imagePath = 'img/processing';
            $processingImage = $this->uploadImage($imagePath, $request->attchedPic);
        }

        // store processing product
        $processingProduct = ProcessingProduct::create([
            'purchase_id' => $request->purcahseProduct,
            'processing_code' => $processingCode,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'processing_image' => $processingImage,
            'status' => $request->status,
            'note' => clean($request->note),
        ]);

        // store processing product steps and staff
        $processingSteps = $request->steps;
        foreach ($processingSteps as $step) {
            if (!empty($request["processingStaff" . $step])) {
                foreach ($request["processingStaff" . $step] as $key => $staff) {
                    if ($staff) {
                        $processingProduct->selectedStaff()->attach($staff, ['processing_step_id' => $step]);
                    }
                }
            }
        }
        return redirect()->route('processing.index')->withSuccess('Processing product created successfully!');
    }

    /**
     * Display the specified processing product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $processing = ProcessingProduct::where('slug', $slug)->first();
        $steps = DB::table('processing_product_staff')->select('processing_step_id')->distinct()->join('processing_steps', 'processing_steps.id', '=', 'processing_step_id')->select('processing_product_staff.processing_step_id as selected_step_id', 'processing_steps.name as pivot_step_name', 'processing_steps.id as pivot_step_id')->select('processing_steps.name as step_name', 'processing_steps.id as step_id')->get();
        $finishedProducts = $processing->finishedProducts()->latest()->paginate(10);
        return view('admin.processing.show', compact('processing', 'steps', 'finishedProducts'));
    }

    /**
     * Show the form for editing the specified procesing product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $processing = ProcessingProduct::where('slug', $slug)->first();
        $staff = Staff::where('status', 1)->latest()->get();
        $purchases = Purchase::where('status', 1)->latest()->get();
        $processingSteps = DB::table('processing_product_staff')->select('processing_step_id')->distinct()->join('processing_steps', 'processing_steps.id', '=', 'processing_step_id')->select('processing_product_staff.processing_step_id as selected_step_id', 'processing_steps.name as pivot_step_name', 'processing_steps.id as pivot_step_id')->select('processing_steps.name as step_name', 'processing_steps.id as step_id')->get();
        return view('admin.processing.edit', compact('staff', 'purchases', 'processingSteps', 'processing'));
    }

    /**
     * Update the specified processing product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $processing = ProcessingProduct::where('slug', $slug)->first();

        // validate form
        $validator = $request->validate([
            'purcahseProduct' => 'required|integer',
            "processingStaff*"  => "required|array|min:1",
            'startDate' => 'required|date|date_format:Y-m-d',
            'endDate' => 'nullable|date|date_format:Y-m-d|after:startDate',
            'note' => 'nullable|string|max:255',
            'attchedPic' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        // delete processing image and upload new image
        $processingImage = $processing->processing_image;
        if (isset($request->attchedPic)) {
            if (isset($processingImage)) {
                $this->deleteImage('img/processing/' . $processingImage);
            }
            $imagePath = 'img/processing';
            $processingImage = $this->uploadImage($imagePath, $request->attchedPic);
        }

        // update processing product
        $processing->update([
            'purchase_id' => $request->purcahseProduct,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'processing_image' => $processingImage,
            'status' => $request->status,
            'note' => clean($request->note),
        ]);

        // update processing product staff and steps
        DB::table('processing_product_staff')->where('processing_product_id', $processing->id)->delete();
        $processingSteps = DB::table('processing_product_staff')->select('processing_step_id')->distinct()->join('processing_steps', 'processing_steps.id', '=', 'processing_step_id')->select('processing_product_staff.processing_step_id as selected_step_id', 'processing_steps.name as pivot_step_name', 'processing_steps.id as pivot_step_id')->select('processing_steps.name as step_name', 'processing_steps.id as step_id')->get();
        foreach ($processingSteps as $step) {
            foreach ($request["processingStaff" . $step->step_id] as $key => $staff) {
                $processing->selectedStaff()->attach($staff, ['processing_step_id' => $step->step_id]);
            }
        }
        return redirect()->route('processing.index')->withSuccess('Processing product updated successfully!');
    }

    /**
     * Remove the specified processing product from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $processing = ProcessingProduct::where('slug', $slug)->first();

        // delete image from storage
        if (isset($processing->processing_image)) {
            $this->deleteImage('img/processing/' . $processing->processing_image);
        }

        // delete processing product
        $processing->delete();
        return redirect()->route('processing.index')->withSuccess('Processing product deleted successfully!');
    }



    /**
     * Change the specified processing product status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $processing = ProcessingProduct::where('slug', $slug)->first();

        // change current status of the processing product
        if ($processing->isActive()) {
            $processing->update([
                'status' => 0
            ]);
        } else {
            $processing->update([
                'status' => 1
            ]);
        }
        return redirect()->route('processing.index')->withSuccess('Processing product status changed successfully!');
    }

    // create pdf
    public function createPDF()
    {
        $processingProducts = ProcessingProduct::with('purchase')->orderBy('id', 'DESC')->get();
        $steps = DB::table('processing_product_staff')->select('processing_step_id')->distinct()->join('processing_steps', 'processing_steps.id', '=', 'processing_step_id')->select('processing_product_staff.processing_step_id as selected_step_id', 'processing_steps.name as pivot_step_name', 'processing_steps.id as pivot_step_id')->select('processing_steps.name as step_name', 'processing_steps.id as step_id')->get();
        // share data to view
        //view()->share('suppliers', $data);
        $pdf = PDF::loadView('admin.pdf.processing', compact('processingProducts', 'steps'));
        // download PDF file with download method
        return $pdf->download('processing-products-list.pdf');
    }
}
