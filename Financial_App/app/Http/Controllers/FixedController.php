<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FixedModel;
use Illuminate\Validation\ValidationException;

class FixedController extends Controller
{
    public function addFixed(Request $request)
{
    try {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date_time' => 'required|date',
            'is_paid' => 'required|boolean',
            'type' => 'required|in:inc,exp',
            'scheduled_date' => 'required|in:year,month,week,day,hour,minute,second'
        ]);

        $fixed = FixedModel::create($validatedData);

        return response()->json([
            "message " => $fixed
        ]);
    } catch (ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


public function deleteFixed($id)
{
    $fixed = FixedModel::find($id);

    if (!$fixed) {
        return response()->json(['error' => $id.' not found'], 404);
    }

    try {
        $fixed->delete();
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to delete fixed'], 500);
    }

    return response()->json([
        'message' => 'Fixed deleted successfully'
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