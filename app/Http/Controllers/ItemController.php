<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Department;
use App\Models\SubCategory;
use App\Models\Size;
use Illuminate\Http\Request;
use PDF;

class ItemController extends Controller
{
    /**
     * Display a listing of the sub categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Items::query();
        if (request('term')) {
            $term = request('term');
            $query = Items::where('item', 'Like', '%' . $term . '%')
                ->orWhere('note', 'Like', '%' . $term . '%')
                ->orWhereHas('categoryName', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%' . $term . '%');
                });
        }
        $items = $query->orderBy('id', 'DESC')->paginate(15);
        return view('admin.items.index', compact('items'));
    }

    /**
     * Show the form for creating a new sub category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $categories = Category::all();
        $SubCategory = SubCategory::all();
        // $suppliers = Supplier::where('status', 1)->latest()->get();
        $sizes = Size::where('status', 1)->get();
        $departments = Department::all();
        return view('admin.items.create', compact('categories', 'SubCategory', 'sizes', 'departments'));
    }

    /**
     * Store a newly created sub category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate form
        $validator = $request->validate([
            'item' => 'required|string|max:60|unique:items',
            'pnumber' => 'required|unique:items,pnumber',
            'categoryName' => 'required|integer',
            'department_id' => 'required',

        ]);

      try{

        $items = new Items();
        $items->item = $request->item;
        $items->pnumber = $request->pnumber;
        $items->category_id = $request->categoryName;
        $items->department_id = $request->department_id;
        $items->status = $request->status;
        $items->save();
        return response()->json([
            'message' =>'Item Created Successfully',
            'item' => $items,
        ],201);
        }
        catch (Exception $ex){
            return $ex;
        }
       // return redirect()->back()->withSuccess('Item created successfully!');

    }

    /**
     * Display the specified sub category.
     *
     * @param  \App\Models\Items  $Storein
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return redirect()->route('items.index');
    }

    /**
     * Show the form for editing the specified sub category.
     *
     * @param  \App\Models\Items  $Storein
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $categories = Category::all();
        $subCategory = SubCategory::all();
        $suppliers = Supplier::where('status', 1)->latest()->get();
        return view('admin.items.edit', compact('categories', 'subCategory', 'suppliers'));
    }

    /**
     * Update the specified sub category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $subCategory = Items::where('slug', $slug)->first();

        // validate form
        // $validator = $request->validate([
        //     'name' => 'required|string|max:60|unique:sub_categories,name,' . $subCategory->id,
        //     'categoryName' => 'required|integer',
        //     'subcategoryName' => 'required|integer',
        // ]);

        // convert sizes into string
        $sizes = implode(', ', $request->sizes);

        // update subcategory
        $subCategory->update([
            'name' => $request->name,
            'pnumber' => $request->pnumber,
            'category_id' => $request->categoryName,
            'sub_cat_id' => $request->subcategory,
            // 'supplier_id' => $request->supplier,
            'status' => $request->status
        ]);
        return redirect()->route('items.index')->withSuccess('Items updated successfully!');
    }

    /**
     * Remove the specified sub category from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $subCategory = Items::where('slug', $slug)->first();

        // destroy sub category
        $subCategory->delete();
        return redirect()->route('items.index')->withSuccess('Sub category deleted successfully!');
    }

    /**
     * Change the status of specified sub category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $subCategory = Items::where('slug', $slug)->first();

        // change sub category status
        if ($subCategory->status == 1) {
            $subCategory->update([
                'status' => 0
            ]);
        } else {
            $subCategory->update([
                'status' => 1
            ]);
        }
        return redirect()->route('items.index')->withSuccess('Sub category status changed successfully!');
    }

    // create pdf
    // public function createPDF()
    // {
    //     // retreive all records from db
    //     $data = SubCategory::with('category')->latest()->get();
    //     // share data to view
    //     view()->share('items', $data);
    //     // $pdf = PDF::loadView('admin.pdf.items', $data->all());
    //     // download PDF file with download method
    //     // return $pdf->download('items-list.pdf');
    // }
}
