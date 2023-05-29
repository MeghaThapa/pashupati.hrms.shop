<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcessingCategoryAndSubsModel;

class ProcessingCategoryAndSubsController extends Controller
{
    public function categoryindex(){
        $data = ProcessingCategoryAndSubsModel::where('status','active')->paginate(10);
        return view('admin.setup.processing_categories_and_subs.categoriesindex')->with(['data'=>$data]);
    }
    public function categorycreate(){
        // $data = 
        return view('admin.setup.processing_categories_and_subs.categoriesindex');
    }
    public function categorystore(Request $request){
        $request->validate([
            'name' => 'required',
            'status' => "required"
        ]);
        
    }
}
