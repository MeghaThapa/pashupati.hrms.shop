<?php

namespace App\Http\Controllers;
use App\Models\ProcessingSubcat;
use App\Models\Department;
use App\Models\Godam;
use App\Models\ProcessingStep;
use Illuminate\Http\Request;

class ProcessingSubcatController extends Controller
{
    /**
     * Display a listing of the processing steps.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $processingSteps = ProcessingSubcat::with('processingSteps','godam')->latest()->paginate(15);
        return view('admin.setup.processing-subcat.index', compact('processingSteps'));
    }

    public function getProcessingStepsAccDept($godam_id){
        $ProcessingSteps = ProcessingStep::where('godam_id',$godam_id)->get();
         return response()->json(
            [
                'processingSteps' => $ProcessingSteps
            ],
            200
        );
    }

    /**
     * Show the form for creating a new processing step.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $processingSubCat =null;
        $godams=Godam::all()->where('status','active');
       // return $godams;
        return view('admin.setup.processing-subcat.create', compact('godams','processingSubCat'));
    }
    public function edit($processingSubCatId){
        $processingSubCat =Processingsubcat::find($processingSubCatId);
       // return $processingSubCat;
         $godams = Godam::all()->where('status','active');
        return view('admin.setup.processing-subcat.create', compact('godams','processingSubCat'));
    }

    /**
     * Store a newly created processing step in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate form
      //  return $request;
        $validator = $request->validate([
            'name' => 'required|string|max:30|unique:processing_subcats,name',
            'processing_steps_id' => 'required',
            'status' => 'required',
            // 'department_id'=>'required'
        ]);

        // store processing subcat
        $processingSubcat = Processingsubcat::create([
            'name' => strtolower($request->name),
            'processing_steps_id' =>$request->processing_steps_id,
            'slug' =>$request->name,
            'status' => $request->status,
            // 'department_id' => $request->department_id,
        ]);
        if (request()->ajax()){
            return response()->json([
                'message' =>'Processing subcat Created Successfully',
                'processingSubcat' => $processingSubcat,
            ],201);
        }
        return redirect()->route('processing-subcat.index')->withSuccess('Processing step added successfully!');
    }

    /**
     * Display the specified processing step.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return redirect()->route('processing-subcat.index');
    }

    /**
     * Show the form for editing the specified processing step.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    // public function edit($slug)
    // {
    //     $department = Department::all();
    //     $processingStep = Processingsubcat::where('slug', $slug)->first();
    //     return view('admin.setup.processing-subcat.edit', compact('processingStep','department'));
    // }

    /**
     * Update the specified processing step in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $processingSubCatId)
    {
        $processingStep = Processingsubcat::find($processingSubCatId);


        // validate form
        $validator = $request->validate([
            'name' => 'required|string|max:30|unique:processing_steps,name,'.$processingStep->id,
            'processing_steps_id' =>'required',
            'status' =>'required',
            // 'department_id'=>'required'
        ]);

        // update processing step
        $processingStep->update([
             'name' => strtolower($request->name),
            'processing_steps_id' =>$request->processing_steps_id,
            'slug' =>$request->name,
            'status' => $request->status,
            // 'department_id' => $request->department_id,
        ]);
        return redirect()->route('processing-subcat.index')->withSuccess('Processing step updated successfully!');
    }

    /**
     * Remove the specified processing step from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $processingStep = Processingsubcat::where('slug', $slug)->first();

        // delete processing step
        $processingStep->delete();
        return redirect()->route('processing-subcat.index')->withSuccess('Processing step deleted successfully!');
    }

    /**
     * Change the status of specified size.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $processingStep = Processingsubcat::where('slug', $slug)->first();

        // change status
        if($processingStep->status == 1)
        {
            $processingStep->update([
                'status' => 0
            ]);
        }
        else
        {
            $processingStep->update([
                'status' => 1
            ]);
        }
        return redirect()->route('processing-subcat.index')->withSuccess('Processing step status changed successfully!');
    }
}
