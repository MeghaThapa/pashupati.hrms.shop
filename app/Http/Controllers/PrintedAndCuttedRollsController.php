<?php

namespace App\Http\Controllers;

use App\Models\PrintedAndCuttedRolls;
use App\Models\PrintedAndCuttedRollsEntry;
use Illuminate\Http\Request;

class PrintedAndCuttedRollsController extends Controller
{
    /*************** For Entry *********/
    public function index(){
        $data = PrintedAndCuttedRollsEntry::all();
        return view("admin.bag.printsandcuts.index",compact("data"));
    }
    public function createEntry(){
        return view("admin.bag.printsandcuts.createEntry");
    }

    public function storeEntry(Request $request){
       $request->validate([
        "receipt_number" => "required",
        "date" => "required",
        "date_np" => "required"
       ]);

       PrintedAndCuttedRollsEntry::create([
            "receipt_number" => $request->receipt_number,
            "date" => $request->date,
            "date_np" => $request->date_np
       ]);

       return $this->index();
    }
    /*************** For Entry *********/
    public function createPrintedRolls($id){
        $data = PrintedAndCuttedRollsEntry::where('id',$id)->get(); 
        return view("admin.bag.printsandcuts.create")->with([
            "data" => $data
        ]);
    }
}
