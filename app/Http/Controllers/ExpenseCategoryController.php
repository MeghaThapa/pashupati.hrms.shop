<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use PDF;


class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the expensec category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = ExpenseCategory::query();
        if (request('term')) {
            $term = request('term');
            $query->where('name', 'Like', '%' . $term . '%');
        }
        $categories = $query->orderBy('id', 'DESC')->paginate(15);
        return view('admin.expense-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new expense category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.expense-categories.create');
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
            'name' => 'required|string|max:50|unique:expense_categories',
            'note' => 'nullable|string|max:255',
        ]);

        // store category
        $category = ExpenseCategory::create([
            'name' => $request->name,
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->route('expCategories.index')->withSuccess('Category added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return redirect()->route('expCategories.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $category = ExpenseCategory::where('slug', $slug)->first();
        return view('admin.expense-categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $category = ExpenseCategory::where('slug', $slug)->first();

        //validate form
        $validator = $request->validate([
            'name' => 'required|string|max:50|unique:expense_categories,name,' . $category->id,
            'note' => 'nullable|string|max:255',
        ]);

        // update category
        $category->update([
            'name' => $request->name,
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->route('expCategories.index')->withSuccess('Category updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $category = ExpenseCategory::where('slug', $slug)->first();
        // destroy category
        $category->delete();
        return redirect()->route('expCategories.index')->withSuccess('Category deleted successfully!');
    }


    /**
     * Change the status of specified category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $category = ExpenseCategory::where('slug', $slug)->first();

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
        return redirect()->route('expCategories.index')->withSuccess('Category status changed successfully!');
    }

    // create pdf
    public function createPDF()
    {
        // retreive all records from db
        $data = ExpenseCategory::latest()->get();
        // share data to view
        view()->share('categories', $data);
        $pdf = PDF::loadView('admin.pdf.expense-categories', $data->all());
        // download PDF file with download method
        return $pdf->download('expense-categories-list.pdf');
    }
}
