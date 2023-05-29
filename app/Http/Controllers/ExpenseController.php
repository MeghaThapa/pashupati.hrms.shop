<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use PDF;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Expense::query();
        $expenses = $query->with('expenseCategory')->orderBy('id', 'DESC')->paginate(15);
        if (request('term')) {
            $term = request('term');
            $query->where('expense_reason', 'Like', '%' . $term . '%')
                ->orWhere('amount', 'like', '%' . $term . '%')
                ->orWhereHas('expenseCategory', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%' . $term . '%');
                });
            $expenses = $query->orderBy('id', 'DESC')->paginate(15);
        }
        return view('admin.expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ExpenseCategory::where('status', 1)->get();
        return view('admin.expenses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate form
        $validator = $request->validate([
            'categoryName' => 'required|integer',
            'expenseReason' => 'required|string|max:255|min:4',
            'expenseAmount' => 'required|numeric',
            'expenseDate' => 'required|date',
            'note' => 'nullable|string|max:255',
            'expenseAttachment' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);


        //upload selected image
        $imageName = '';
        if (isset($request->expenseAttachment)) {
            $imagePath = 'img/expenses';
            $imageName = $this->uploadImage($imagePath, $request->expenseAttachment);
        }

        // store expense
        $expense = Expense::create([
            'exp_cat_id' => $request->categoryName,
            'expense_reason' => $request->expenseReason,
            'amount' => $request->expenseAmount,
            'expense_date' => $request->expenseDate,
            'expense_image' => $imageName,
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->route('expenses.index')->withSuccess('Category added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $expense = Expense::where('slug', $slug)->first();
        return view('admin.expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $expense = Expense::where('slug', $slug)->first();
        $categories = ExpenseCategory::where('status', 1)->get();
        return view('admin.expenses.edit', compact('categories', 'expense'));
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
        $expense = Expense::where('slug', $slug)->first();

        //validate form
        $validator = $request->validate([
            'categoryName' => 'required|integer',
            'expenseReason' => 'required|string|max:255|min:4',
            'expenseAmount' => 'required|numeric',
            'expenseDate' => 'required|date',
            'note' => 'nullable|string|max:255',
            'expenseAttachment' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $imageName = $expense->expense_image;

        // remove current image and upload new image
        if (isset($request->expenseAttachment)) {
            $imagePath = 'img/expenses/';
            $this->deleteImage($imagePath . $imageName);
            $imageName = $this->uploadImage($imagePath, $request->expenseAttachment);
        }

        // update expense
        $expense->update([
            'exp_cat_id' => $request->categoryName,
            'expense_reason' => $request->expenseReason,
            'amount' => $request->expenseAmount,
            'expense_date' => $request->expenseDate,
            'expense_image' => $imageName,
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->route('expenses.index')->withSuccess('Expense updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $expense = Expense::where('slug', $slug)->first();
        // delete staff image from storage
        $this->deleteImage('img/expenses/' . $expense->expense_image);
        $expense->delete();
        return redirect()->route('expenses.index')->withSuccess('Expense deleted successfully!');
    }

    /**
     * Change the status of specified expense.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $expense = Expense::where('slug', $slug)->first();

        // change category status
        if ($expense->status == 1) {
            $expense->update([
                'status' => 0
            ]);
        } else {
            $expense->update([
                'status' => 1
            ]);
        }
        return redirect()->route('expenses.index')->withSuccess('Expense status changed successfully!');
    }

    // create pdf
    public function createPDF()
    {
        // retreive all records from db
        $data = Expense::latest()->get();
        // share data to view
        view()->share('expenses', $data);
        $pdf = PDF::loadView('admin.pdf.expenses', $data->all());
        // download PDF file with download method
        return $pdf->download('expenses-list.pdf');
    }
}
