<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function getIncomes(Request $request)
    {
        try {
            $incomes = Income::with('category')->get();
            return response()->json([
                'status' => true,
                'message' => 'This outcome',
                'data' => $incomes,

            ], 201, );


        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => 'Error while getting outcome',
                'error' => $th,

            ]);
        }

    }

    public function addIncome(Request $request)
    {
        echo 'Hello';
        try {
            $request->validate(
                [
                    'title' => 'required|string|max:20',
                    'description' => 'string|max:1000',
                    'amount' => 'required|numeric',
                    'category_id' => 'required|integer',
                    'is_recurring' => 'boolean',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                ],
                [
                    'title.required' => 'The title field is required.',
                    'title.max' => 'The title may not be greater than :max characters.',
                    'description.max' => 'The description may not be greater than :max characters.',
                    'amount.required' => 'The amount field is required.',
                    'amount.numeric' => 'The amount field must be a number.',
                    'category_id.required' => 'The category id field is required.',
                    'is_recurring.boolean' => 'The is recurring field must be a boolean.',
                    'start_date.required' => 'The start date field is required.',
                    'start_date.date' => 'The start date field must be a valid date.',
                    'end_date.required' => 'The end date field is required.',
                    'end_date.date' => 'The end date field must be a valid date.',
                ]
            );

            // $request -> category_id =json_decode( $request->category_id);
            $income = new Income;
            $income->fill($request->all());
            $income->date_time = now();
            $income->save();
            $income->category()->associate($request->category_id);
            return response()->json([
                'message' => 'Income created successfully!',
                'data' => $income
            ], 200);
        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => 'Error while adding income',
                'error' => $th->getMessage(),

            ]);

        }
    }

    public function updateIncome(Request $request, $id)
    {
        echo "Hello";
        $request->validate([
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'amount' => 'sometimes|required|numeric',
            'category_id' => 'sometimes|required|exists:categories,id',
            'is_recurring' => 'sometimes|required|boolean',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
        ]);


        try {
            $income = Income::findOrFail($id);

            $updateFields = [];

            if ($request->has('title')) {
                $updateFields['title'] = $request->input('title');
            }
            if ($request->has('description')) {
                $updateFields['description'] = $request->input('description');
            }
            if ($request->has('amount')) {
                $updateFields['amount'] = $request->input('amount');
            }
            if ($request->has('category_id')) {
                $updateFields['category_id'] = $request->category_id;
            }
            if ($request->has('is_recurring')) {
                $updateFields['is_recurring'] = $request->input('is_recurring');
            }
            if ($request->has('start_date')) {
                $updateFields['start_date'] = $request->input('start_date');
            }
            if ($request->has('end_date')) {
                $updateFields['end_date'] = $request->input('end_date');
            }

            $income->update($updateFields);

            $updatedFields = $income->fresh();

            return response()->json([
                'status' => true,
                'message' => 'Income updated successfully',
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
    public function deleteIncome(Request $request, $id)
    {
        try {
            $income = Income::findOrFail($id);
            $income->delete();
            return response()->json([
                'status' => true,
                'Message' => 'Successfully deleted income'

            ], 200);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while deleted income',
                'error' => $err->getMessage(),
            ], 404);
        }
    }

    public function sortByDateAsc(Request $request)
    {
        try {
            $res = Income::orderBy('date_time', 'asc')
                ->get();
            return response()->json([
                'status' => 'success',
                'SortByDateAsc' => $res

            ], 200);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while deleted income',
                'error' => $err->getMessage(),
            ], 404);
        }
    }

    public function sortByDateDesc(Request $request)
    {
        try {
            $res = Income::orderBy('date_time', 'desc')
                ->get();
            return response()->json([
                'status' => 'success',
                'SortByDateAsc' => $res

            ], 200);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while deleted income',
                'error' => $err->getMessage(),
            ], 404);
        }
    }
    //sortByAmountDesc
    public function sortByAmountDesc(Request $request)
    {
        try {
            $res = Income::orderBy('amount', 'desc')
                ->get();
            return response()->json([
                'status' => 'success',
                'SortByDateAsc' => $res

            ], 200);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while deleted income',
                'error' => $err->getMessage(),
            ], 404);
        }
    }
    //sortByTitleDesc

    public function sortByTitleDesc(Request $request)
    {
        try {
            $res = Income::orderBy('title', 'desc')
                ->get();
            return response()->json([
                'status' => 'success',
                'SortByDateAsc' => $res

            ], 200);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while deleted income',
                'error' => $err->getMessage(),
            ], 404);
        }
    }
}