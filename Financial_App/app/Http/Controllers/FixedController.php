<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FixedModel;

class FixedController extends Controller
{
    public function addFixed(Request $request)
    {
        $expense = new FixedModel ;
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

    public function deleteFixed($id)
    {
        $Fixed = FixedModel::find($id);

        $Fixed->delete();

        return response()->json([
            'message' => 'delete succs'
        ]);
    }
    public function editFixed(Request $request, $id)
    {
        $fixed = FixedModel::find($id);
        $inputs = $request->except('_method');
        $fixed->update($inputs);
        return response()->json([
            'message' => 'fixed expenses updated successfully',
            'fixedExpenses' => $fixed,
        ]);
    }
    //get all expenses
    public function getFixed()
    {
        $Fixed = FixedModel::all();

        return response()->json([
            'Fixed' => $Fixed,
        ]);
    }

    //get  Fixed by ID
    public function getFixedById($id)
    {
        $Fixed = FixedModel::find($id);

        return response()->json([
            'Fixed' => $Fixed,
        ]);
    }
}