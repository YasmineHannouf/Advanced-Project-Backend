<?php

namespace App\Http\Controllers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use App\Models\FixedModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
// use Illuminate\Console\Scheduling\Schedule;


class FixedController extends Controller
{

    public function store(Request $request, Schedule $schedule)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0|not_in:0',
                'category_id' => 'required|integer',
                'key_id' => 'required|integer',
                'type' => ['required', Rule::in(['inc', 'exp'])],
                'scheduled_date' => 'required|in:year,month,week,day,hour'
            ]);
    
            $validatedData = $request->except('_method');
    
            $validatedData['date_time'] = now();
            $validatedData['is_paid'] = true;
            $fixed = FixedModel::create($validatedData);
            $fixed->save(); // save the fixed model here
    
            if ($validatedData['scheduled_date'] == "hour") {
                $schedule->call(function () use ($validatedData) {
                    $record = new FixedModel($validatedData);
                    $record->save();
                })->hourly();
                
                // return response()->json([
                //     "message" => $fixed
                // ]);
            }
    
            if ($validatedData['scheduled_date'] == "day") {
                $schedule->call(function () use ($validatedData) {
                    $record = new FixedModel($validatedData);
                    $record->save();
                })->daily();
            }
    
            if ($validatedData['scheduled_date'] == "month") {
                $schedule->call(function () use ($validatedData) {
                    $record = new FixedModel($validatedData);
                    $record->save();
                })->monthly();
            }
    
            if ($validatedData['scheduled_date'] == "week") {
                $schedule->call(function () use ($validatedData) {
                    $record = new FixedModel($validatedData);
                    $record->save();
                })->weekdays();
            }
    
            if ($validatedData['scheduled_date'] == "year") {
                $schedule->call(function () use ($validatedData) {
                    $record = new FixedModel($validatedData);
                    $record->save();
                })->yearly();
            }
    
            return response()->json([
                "message " => $fixed
            ]);
    
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
    
    




    /**
     * Calculates the date/time when a fixed transaction should occur based on the scheduled_date field.
     *
     * @param string $scheduledDate
     * @return string
     */
    private function calculateScheduledDate($scheduledDate)
    {
        $now = now();
        switch ($scheduledDate) {
            case 'year':
                $date = $now->addYear();
                break;
            case 'month':
                $date = $now->addMonth();
                break;
            case 'week':
                $date = $now->addWeek();
                break;
            case 'day':
                $date = $now->addDay();
                break;
            case 'hour':
                $date = $now->addHour();
                break;
            default:
                $date = $now;
                break;
        }
        return $date;
    }



    public function delete($id)
    {
        $fixed = FixedModel::find($id);

        if (!$fixed) {
            return response()->json(['error' => $id . ' not found'], 404);
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

    public function edit(Request $request, $id)
    {
        try {
            $fixed = FixedModel::find($id);
            if (!$fixed) {
                return response()->json([
                    'error' => 'Fixed expense/income not found',
                ], 404);
            }

            $validatedData = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|nullable|string',
                'amount' => 'sometimes|required|numeric|min:0|not_in:0',
                'category_id' => 'sometimes|required|exists:categories,id',
                'key_id' => 'sometimes|nullable|exists:keys,id',
                'is_paid' => 'sometimes|required|boolean',
                'type' => 'sometimes|required|string|in:fixed,variable',
                'scheduled_date' => 'sometimes|required|in:year,month,week,day,hour,minute,second'
            ]);

            if (empty($validatedData)) {
                return response()->json([
                    'error' => 'No input provided',
                ], 422);
            }

            $fixed->update($validatedData);

            return response()->json([
                'message' => 'Fixed expenses/incomes updated successfully',
                'fixedExpenses' => $fixed,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while updating fixed expenses/incomes',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    //get all expenses
    public function show() //get Fixed

    {
        try {
            $fixed = FixedModel::paginate(10);             //all()

            if ($fixed->isEmpty()) {
                return response()->json([
                    'error' => 'No fixed expenses found',
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

    //get  Fixed by ID
    public function getFixedById($id)
    {
        try {
            $fixed = FixedModel::find($id);

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
    public function getByKeyId($keyId)
    {
        try {
            $fixed = FixedModel::where('key_id', $keyId)->get();

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

    public function getByTitle($title)
    {
        try {
            $fixed = FixedModel::where("title", $title)->get();

            if ($fixed->isEmpty()) {
                return response()->json([
                    'error' => 'No fixed expenses found with the specified title',
                    'fixed' => $fixed,
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
            case 'key_id':
                return $this->getByKeyId($value);
            case 'id':
                return $this->getFixedById($value);
            default:
                return response()->json([
                    'error' => 'Invalid filter specified',
                ], 400);
        }
    }

}
