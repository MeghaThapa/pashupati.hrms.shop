<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Size;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use PDF;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the sub categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = SubCategory::query();
        if (request('term')) {
            $term = request('term');
            $query = SubCategory::where('name', 'Like', '%' . $term . '%')
                ->orWhere('note', 'Like', '%' . $term . '%')
                ->orWhereHas('category', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%' . $term . '%');
                });
        }
        $subCategories = $query->orderBy('id', 'DESC')->paginate(15);
        return view('admin.sub-categories.index', compact('subCategories'));
    }

    /**
     * Show the form for creating a new sub category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('status', 1)->get();
        $sizes = Size::where('status', 1)->get();
        return view('admin.sub-categories.create', compact('categories', 'sizes'));
    }

    /**
     * Store a newly created sub category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate form
        $validator = $request->validate([
            'name' => 'required|string|max:60|unique:sub_categories',
            'categoryName' => 'required|integer',
            'note' => 'nullable|string|max:255',
            "sizes"    => "required|array|min:1",
            "sizes.*"  => "required|string|distinct",
        ]);

        // convert sizes into string
        $sizes = implode(', ', $request->sizes);

        // store subcategory
        $subCategory = SubCategory::create([
            'name' => $request->name,
            'category_id' => $request->categoryName,
            'sizes' => $sizes,
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->back()->withSuccess('Sub category created successfully!');
    }

    /**
     * Display the specified sub category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return redirect()->route('subCategories.index');
    }

    /**
     * Show the form for editing the specified sub category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $categories = Category::where('status', 1)->get();
        $subCategory = SubCategory::where('slug', $slug)->first();
        $selectedSizes = explode(", ", $subCategory->sizes);
        $sizes = Size::where('status', 1)->get();
        return view('admin.sub-categories.edit', compact('categories', 'subCategory', 'sizes', 'selectedSizes'));
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
        $subCategory = SubCategory::where('slug', $slug)->first();

        // validate form
        $validator = $request->validate([
            'name' => 'required|string|max:60|unique:sub_categories,name,' . $subCategory->id,
            'categoryName' => 'required|integer',
            'note' => 'nullable|string|max:255',
            "sizes"    => "required|array|min:1",
            "sizes.*"  => "required|string|distinct",
        ]);

        // convert sizes into string
        $sizes = implode(', ', $request->sizes);

        // update subcategory
        $subCategory->update([
            'name' => $request->name,
            'category_id' => $request->categoryName,
            'sizes' => $sizes,
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->route('subCategories.index')->withSuccess('Sub category updated successfully!');
    }

    /**
     * Remove the specified sub category from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $subCategory = SubCategory::where('slug', $slug)->first();

        // destroy sub category
        $subCategory->delete();
        return redirect()->route('subCategories.index')->withSuccess('Sub category deleted successfully!');
    }

    /**
     * Change the status of specified sub category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $subCategory = SubCategory::where('slug', $slug)->first();

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
        return redirect()->route('subCategories.index')->withSuccess('Sub category status changed successfully!');
    }

    // create pdf
    public function createPDF()
    {
        // retreive all records from db
        $data = SubCategory::with('category')->latest()->get();
        // share data to view
        view()->share('subCategories', $data);
        $pdf = PDF::loadView('admin.pdf.sub-categories', $data->all());
        // download PDF file with download method
        return $pdf->download('sub-categories-list.pdf');
    }
}
