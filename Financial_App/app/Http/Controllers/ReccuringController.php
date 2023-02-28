<?php

namespace App\Http\Controllers;
use App\Models\reccuringModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReccuringController extends Controller
{
    public function getRecurring(Request $request)
    {
        try {
            $Recurring = reccuringModel::with('category')->get();
            return response()->json([
                'status' => true,
                'message' => 'This outcome',
                'data' => $Recurring,

            ], 201, );


        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => 'Error while getting outcome',
                'error' => $th,

            ]);
        }

    }

    // public function addRecurring(Request $request)
    // {
    //     echo 'Hello';
    //     try {
    //         $request->validate(
    //             [
    //                 'title' => 'required|string|max:20',
    //                 'description' => 'string|max:1000',
    //                 'amount' => 'required|numeric',
    //                 'category_id' => 'required|integer',
    //                 'start_date' => 'required|date',
    //                 'end_date' => 'required|date|after_or_equal:start_date',
    //                 'type' => 'required|date|after_or_equal:start_date',
    //             ],
    //             [
    //                 'title.required' => 'The title field is required.',
    //                 'title.max' => 'The title may not be greater than :max characters.',
    //                 'description.max' => 'The description may not be greater than :max characters.',
    //                 'amount.required' => 'The amount field is required.',
    //                 'amount.numeric' => 'The amount field must be a number.',
    //                 'category_id.required' => 'The category id field is required.',
    //                 'is_recurring.boolean' => 'The is recurring field must be a boolean.',
    //                 'start_date.required' => 'The start date field is required.',
    //                 'start_date.date' => 'The start date field must be a valid date.',
    //                 'end_date.required' => 'The end date field is required.',
    //                 'end_date.date' => 'The end date field must be a valid date.',
    //             ]
    //         );

    //         $Recurring = new reccuringModel;
    //         $Recurring->fill($request->all());
    //         $Recurring->date_time = now();
    //         $Recurring->save();
    //         $Recurring->category()->associate($request->category_id);
    //         return response()->json([
    //             'message' => 'Recurring created successfully!',
    //             'data' => $Recurring
    //         ], 200);
    //     } catch (\Throwable $th) {

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Error while adding Recurring',
    //             'error' => $th->getMessage(),

    //         ]);

    //     }

    // }
    public function createReccuring(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:20',
            'description' => 'string|max:1000',
            'amount' => 'required|numeric',
            'category_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => ['required', Rule::in(['inc', 'exp'])],
        ], [
            'title.required' => 'The title field is required.',
            'title.max' => 'The title may not be greater than :max characters.',
            'description.max' => 'The description may not be greater than :max characters.',
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount field must be a number.',
            'category_id.required' => 'The category id field is required.',
            'start_date.required' => 'The start date field is required.',
            'start_date.date' => 'The start date field must be a valid date.',
            'end_date.required' => 'The end date field is required.',
            'end_date.date' => 'The end date field must be a valid date.',
            'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
            'type.required' => 'The type field is required.',
            'type.in' => 'The type field must be one of the following values: inc, exp.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Error while creating recurring income',
                'errors' => $validator->errors()
            ]);
        }

        try {
            $recurring = new reccuringModel($request->all());
            $recurring->date_time = now();
            $recurring->category_id = $request->category_id;
            $recurring->save();
            return response()->json([
                'message' => 'Recurring expense/income created successfully!',
                'data' => $recurring
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error while creating recurring expense/income',
                'error' => $th->getMessage(),
            ],500);
        }
    }

    public function updateRecurring(Request $request, $id)
    {
        echo "Hello";
        $request->validate([
            'title' => 'sometimes|required|max:20',
            'description' => 'sometimes|required',
            'amount' => 'sometimes|required|numeric',
            'category_id' => 'sometimes|required|exists:categories,id',
            'is_recurring' => 'sometimes|required|boolean',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
        ]);


        try {
            $Recurring = reccuringModel::findOrFail($id);

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

            $Recurring->update($updateFields);

            $updatedFields = $Recurring->fresh();

            return response()->json([
                'status' => true,
                'message' => 'Recurring updated successfully',
                'data' => $updatedFields,
            ], 201);
        } catch (\Exception $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while updating Recurring',
                'error' => $err->getMessage(),
            ], 500);
        }

    }
    public function deleteRecurring(Request $request, $id)
    {
        try {
            $Recurring = reccuringModel::findOrFail($id);
            $Recurring->delete();
            return response()->json([
                'status' => true,
                'Message' => 'Successfully deleted Recurring'

            ], 200);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while deleted Recurring',
                'error' => $err->getMessage(),
            ], 404);
        }
    }

    public function sortByDateAsc(Request $request)
    {
        try {
            $res = reccuringModel::orderBy('date_time', 'asc')
                ->get();
            return response()->json([
                'status' => 'success',
                'SortByDateAsc' => $res

            ], 200);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while deleted Recurring',
                'error' => $err->getMessage(),
            ], 404);
        }
    }

    public function sortByDateDesc(Request $request)
    {
        try {
            $res = reccuringModel::orderBy('date_time', 'desc')
                ->get();
            return response()->json([
                'status' => 'success',
                'SortByDateAsc' => $res

            ], 200);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while deleted Recurring',
                'error' => $err->getMessage(),
            ], 404);
        }
    }
    //sortByAmountDesc
    public function sortByAmountDesc(Request $request)
    {
        try {
            $res = reccuringModel::orderBy('amount', 'desc')
                ->get();
            return response()->json([
                'status' => 'success',
                'SortByDateAsc' => $res

            ], 200);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while deleted Recurring',
                'error' => $err->getMessage(),
            ], 404);
        }
    }
    //sortByTitleDesc

    public function sortByTitleDesc(Request $request)
    {
        try {
            $res = reccuringModel::orderBy('title', 'desc')
                ->get();
            return response()->json([
                'status' => 'success',
                'SortByDateAsc' => $res

            ], 200);
        } catch (\Throwable $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while deleted Recurring',
                'error' => $err->getMessage(),
            ], 404);
        }
    }
}
