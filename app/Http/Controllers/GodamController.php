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
    public function create(){
         return view('admin.setup.godam.create');
    }

    public function store(Request $request){
       // return $request;
         $request->validate([
            "name" => "required|unique:godam",
            "status" => "required"
            ]);
        $godam=new Godam();
        $godam->name=$request->name;
        $godam->status=$request->status;
        $godam->save();
        return redirect()->route('godam.index')->with("success", "Godam Created Successfully");
    }

    // public function edit($id){
    //     $godam = Godam::where("id",$id)->get();
    //     return view('admin.setup.godam.edit')->with(['godam'=>$godam,"id"=>$id]);
    // }

        public function edit($godam_id){

        $godam=Godam::find($godam_id);
        //return $godam;
        return view('admin.setup.godam.edit',compact('godam'));

    }
    public function update(Request $request,$id){
       // return $request;
        $request->validate([
            "name" => "required",
            "status" => "required"
            ]);
        // $godam = Godam::where('id',$id)->update([
        //     "name" => $request->name,
        //     "status" => $request->status
        // ]);
        $godam=Godam::find($id);
         $godam->name=$request->name;
        $godam->status=$request->status;
        $godam->save();

        return  redirect()->route('godam.index')->with(["message"=>"Updated Successfully"]);
    }
    public function delete($id){
        $godam = Godam::find($id)->delete();
        return $this->index()->with(["message"=>"Deleted Successfully"]);
    }
}
