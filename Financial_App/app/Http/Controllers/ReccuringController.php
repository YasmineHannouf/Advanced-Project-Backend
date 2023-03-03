<?php

namespace App\Http\Controllers;

use App\Models\Reccuring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

//use Illuminate\Support\Facades\Validator;
//use Illuminate\Validation\Rule;
//use Illuminate\Http\Request;
// use App\Models\Reccuring;
use App\Models\Category;

class ReccuringController extends Controller
{
    public function show(Request $request)
    {
        try {
            // $Recurring = reccuringModel::with('category')->get();
            $Recurring = Reccuring::with('category')->paginate(10);
            if($Recurring->isEmpty()){ return response()->json([
                'status' => false,
                'message' => 'The Table is Empty',
            

            ]);}
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



    public function store(Request $request)
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
            $recurring = new Reccuring($request->all());
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
            ], 500);
        }
    }


   

    public function edit(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|required|max:20',
            'description' => 'sometimes|required',
            'amount' => 'sometimes|required|numeric',
            'category_id' => 'sometimes|required|exists:categories,id',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'type' => ['sometimes', 'required', Rule::in(['inc', 'exp'])],
        ]);

        try {
            $recurring = Reccuring::findOrFail($id);

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
                $updateFields['category_id'] = $request->input('category_id');
            }
            if ($request->has('start_date')) {
                $updateFields['start_date'] = $request->input('start_date');
            }
            if ($request->has('end_date')) {
                $updateFields['end_date'] = $request->input('end_date');
            }
            if ($request->has('type')) {
                $updateFields['type'] = $request->input('type');
            }
            $recurring->update($updateFields);
            $recurring->category()->associate("category_id");
            $updatedFields = $recurring->fresh();

            return response()->json([
                'status' => true,
                'message' => 'Recurring income updated successfully',
                'data' => $updatedFields,
            ], 201);
        } catch (\Exception $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while updating recurring income',
                'error' => $err->getMessage(),
            ], 500);
        }
    }
    public function deleteRecurring(Request $request, $id)
    {
        try {
            $Recurring = Reccuring::findOrFail($id);
            $Recurring->delete();
            return response()->json([
                'status' => true,
                'Message' => 'Successfully deleted Recurring'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error while deleted Recurring',

                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $reccuring = Reccuring::findOrFail($id);
            $reccuring->delete();
            return response()->json([
                'status' => true,
                'message' => 'Successfully deleted reccuring'

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

            $res = Reccuring::orderBy('date_time', 'asc')
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


            $res = Reccuring::orderBy('date_time', 'desc')
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

            $res = Reccuring::orderBy('amount', 'desc')
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

            $res = Reccuring::orderBy('title', 'desc')
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


    public function getById($id)
    {
        try {
            $fixed = Reccuring::find($id);

            if (!$fixed) {
                return response()->json([
                    'error' => 'Fixed expense not found',
                ], 404);
            }

            return response()->json([
                'fixed' => $fixed,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching fixed expense',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function getByType($keyId)
    {
        try {
            $fixed = Reccuring::where('type', $keyId)->get();

            if ($fixed->isEmpty()) {
                return response()->json([
                    'error' => 'No fixed expenses found with the specified key_id',
                ], 404);
            }

            return response()->json([
                'fixed' => $fixed,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching fixed expenses',
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    public function getByTitle($filter, $value)
    {
        try {
            $fixed = Reccuring::where(function ($query) use ($filter, $value) {
                $query->where($filter, 'LIKE', '%' . $value . '%');
            })->get();
                
            if ($fixed->isEmpty()) {
                return response()->json([
                    'error' => 'No fixed expenses found with the specified title',
                ], 404);
            }
    
            return response()->json([
                'fixed' => $fixed,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching fixed expenses',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    



    public function filter($filter, $value)
    {
        switch ($filter) {
            case 'title':
                return $this->getByTitle($value);
            case 'id':
                return $this->getById($value);
            default:
                return response()->json([
                    'error' => 'Invalid filter specified',
                ], 400);
        }
    }
}