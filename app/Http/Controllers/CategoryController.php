<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use PDF;
use Exception;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Category::query();
        if (request('term')) {
            $term = request('term');
            $query->where('name', 'Like', '%' . $term . '%');
        }
        $categories = $query->orderBy('id', 'DESC')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate form
        $validator = $request->validate([
            'name' => 'required|string|max:50|unique:categories',
            'note' => 'nullable|string|max:255',
            'status' => 'required'
        ]);
        try{
        // store category
        $category = Category::create([
            'name' => clean(strtolower($request->name)),
            'note' => clean(strtolower($request->note)),
            'slug' => clean(Str::slug(strtolower($request->name))),
            'status' => clean($request->status)
        ]);
        return response()->json([
            'message' =>'Category Created Successfully',
            'category' => $category,
        ],201);
        }
        catch (Exception $ex){
            return $ex;
        }
    }

    /**
     * Display the specified category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return redirect()->route('categories.index');
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $category = Category::where('slug', $slug)->first();
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->first();

        //validate form
        $validator = $request->validate([
            'name' => 'required|string|max:50|unique:categories,name,' . $category->id,
            'note' => 'nullable|string|max:255',
        ]);

        // update category
        $category->update([
            'name' => $request->name,
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->route('categories.index')->withSuccess('Category updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $category = Category::where('slug', $slug)->first();
        // destroy category
        $category->delete();
        return redirect()->route('categories.index')->withSuccess('Category deleted successfully!');
    }


    /**
     * Change the status of specified category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $category = Category::where('slug', $slug)->first();

        // change category status
        if ($category->status == 1) {
            $category->update([
                'status' => 0
            ]);
        } else {
            $category->update([
                'status' => 1
            ]);
        }
        return redirect()->route('categories.index')->withSuccess('Category status changed successfully!');
    }

    // create pdf
    public function createPDF()
    {
        // retreive all records from db
        $data = Category::latest()->get();
        // share data to view
        view()->share('categories', $data);
        $pdf = PDF::loadView('admin.pdf.categories', $data->all());
        // download PDF file with download method
        return $pdf->download('categories-list.pdf');
    }
}
