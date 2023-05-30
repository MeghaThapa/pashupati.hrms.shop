<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use PDF;

class FabricController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();
        if (request('term')) {
            $term = request('term');
            $query->where('name', 'Like', '%' . $term . '%');
        }
        $categories = $query->orderBy('id', 'DESC')->paginate(15);
        return view('admin.fabric.index', compact('categories'));
    }

     public function create()
    {
        return view('admin.fabric.create');
    }
}
