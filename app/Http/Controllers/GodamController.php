<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Godam;

class GodamController extends Controller
{
    public function index(){
        $godams = Godam::paginate(10);
        $godamCount = $godams->count();
        return view('admin.setup.godam.index')->with(['godams'=>$godams,"count"=>$godamCount]);
    }
    public function create(Request $request){
        $request->validate([
            "name" => "required",
            "status" => "required"
            ]);
        $godam = Godam::create([
            "name" => $request->name,
            "status" => $request->status
        ]);

        return back()->with(["message"=>"Godam Created Succesfully"]);
    }
    
    public function edit($id){
        $godam = Godam::where("id",$id)->get();
        return view('admin.setup.godam.edit')->with(['godam'=>$godam,"id"=>$id]);
    }
    
    public function update(Request $request,$id){
        $request->validate([
            "name" => "required",
            "status" => "required"
            ]);
        $godam = Godam::where('id',$id)->update([
            "name" => $request->name,
            "status" => $request->status
        ]);
        
        return $this->index()->with(["message"=>"Updated Successfully"]);
    }
    public function delete($id){
        $godam = Godam::where("id",$id)->delete();
        return $this->index()->with(["message"=>"Deleted Successfully"]);
    }
}
