<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function addExpenses(Request $request)
    {
        $expense = new Expense;
        $title = $request->input('title');
        $description = $request->input('description');
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        $date_time = $request->input('date_time');
        $category_id = $request->input('category_id');
        $is_recurring = $request->input('is_recurring');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $expense->title = $title;
        $expense->description = $description;
        $expense->amount = $amount;
        $expense->currency = $currency;
        $expense->date_time = $date_time;
        $expense->category_id = $category_id;
        $expense->is_recurring = $is_recurring;
        $expense->start_date = $start_date;
        $expense->end_date = $end_date;
        $expense->save();

        return response()->json([
            "message " => $expense
        ]);
    }

    public function deleteExpenses($id)
    {
        $expense = Expense::find($id);

        $expense->delete();

        return response()->json([
            'message' => 'delete succs'
        ]);
    }
    public function editExpenses(Request $request, $id)
    {
        $fixedExpenses = Expense::find($id);
        $inputs = $request->except('_method');
        $fixedExpenses->update($inputs);
        return response()->json([
            'message' => 'fixed expenses updated successfully',
            'fixedExpenses' => $fixedExpenses,
        ]);
    }
    //get all expenses
    public function getExpenses()
    {
        $fixedExpenses = Expense::all();

        return response()->json([
            'expenses' => $fixedExpenses,
        ]);
    }

    //get  expenses by ID
    public function getExpensesById($id)
    {
        $fixedExpenses = Expense::find($id);

        return response()->json([
            'expenses' => $fixedExpenses,
        ]);
    }
}