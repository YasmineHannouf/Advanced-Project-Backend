<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\profit_goals;

class ProfitGoalsController extends Controller
{
    public function Get_Profit_Goals(Request $Request){
        try{
            $ProfitGoal=Profit_Goals::get();
            return response () -> json ([
                "message"=>$ProfitGoal,
            ],200);
        } catch(\Exception $err){
            return response () -> json([
                'status' => false,
                'message' => 'Error while updating ProfitGoals',
                'error' =>$err -> getMessage(),
            ],500);
        }
    }



    public function Post_Profit_Goals(Request $Request)
    {
         try {

            $Request->validate([
            'year' => 'required|integer',
            'goal' => 'sometimes|required|numeric',
            'start_date' => 'sometimes|required|date_format:Y-m-d',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
        ],
            [
                'year.required' => 'The year field is required.',
                'goal.required' => 'The goal field is required.',
                'goal.numeric' => 'The goal field must be a number.',
                'start_date.required' => 'The start date field is required.',
                'start_date.date_format' => 'The start date field must be in the format Y-m-d.',
                'end_date.required' => 'The end date field is required.',
                'end_date.date' => 'The end date field must be a valid date.',
                'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
            ]);



            $ProfitGoal = new Profit_Goals;
            $year = $Request->input("year");
            $goal = $Request->input("goal");
            $start_date = $Request->input("start_date");
            $end_date = $Request->input("end_date");

             $ProfitGoal->year = $year;
             $ProfitGoal->goal = $goal;
             $ProfitGoal->start_date = $start_date;
             $ProfitGoal->end_date = $end_date;

              $ProfitGoal->save();

        return response()->json([
            "message" => "Profit Goal created",
        ], 200);
    } catch (\Exception $err) {
        return response()->json([
            'status' => false,
            'message' => 'Error while creating profit goal',
            'error' => $err->getMessage(),
        ], 500);
    }
}


public function Delete_Profit_Goals(Request $request, $id)
{
    try {
        $ProfitGoal = Profit_Goals::findOrFail($id);
        $ProfitGoal->delete();
        return response()->json([
            'status' => true,
            'Message' => 'Successfully deleted Profit goal'

        ], 200);
    } catch (\Throwable $err) {
        return response()->json([
            'status' => false,
            'message' => 'Error while deleted Profit goal',
            'error' => $err->getMessage(),
        ], 404);
    }
}


public function Edit_Profit_Goals(Request $request, $id)
{
    $ProfitGoal = Profit_Goals::findOrFail($id);

    $request->validate([
        'year' => 'sometimes|required|integer',
        'goal' => 'sometimes|required|numeric',
        'start_date' => 'sometimes|required|date_format:Y-m-d',
        'end_date' => 'sometimes|required|date|after_or_equal:start_date',
    ]);

    try {

        $updateFields = [];

        if ($request->has('year')) {
            $updateFields['year'] = $request->input('year');
        }
        if ($request->has('goal')) {
            $updateFields['goal'] = $request->input('goal');
        }
        if ($request->has('start_date')) {
            $updateFields['start_date'] = $request->input('start_date');
        }
        if ($request->has('end_date')) {
            $updateFields['end_date'] = $request->input('end_date');
        }

        $ProfitGoal->update($updateFields);

        $updatedFields = $ProfitGoal->fresh();

        return response()->json([
            'status' => true,
            'message' => 'Profit goal updated successfully',
            'data' => $updatedFields,
        ], 201);
    } catch (\Exception $err) {
        return response()->json([
            'status' => false,
            'message' => 'Error while updating income',
            'error' => $err->getMessage(),
        ], 500);
    }
}
}

