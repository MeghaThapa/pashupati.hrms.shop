<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class FabricTransferEntryForBagController extends Controller
{
    public function index(){
        $categories = Category::orderBy("id","DESC")->paginate(20);
        return view('admin.bag.fabric transfer for bag.index',compact('categories'));
    }
    
    public function create(){
        return view("admin.bag.fabric transfer for bag.create");
    }
    public function store(Request $request){
      $request->validate([
        
      ]);
    }
}
