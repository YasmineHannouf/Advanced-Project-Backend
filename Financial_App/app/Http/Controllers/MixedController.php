<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Admins;
use App\Models\Fixedkey;
use App\Models\FixedModel;
use App\Models\Reccuring;
use Illuminate\Http\Request;
use App\Models\profit_goals;


class MixedController extends Controller
{
    public function search(Request $request)
    {
        try {
            $q = $request->input('q');
            $categories = Category::where('name', 'LIKE', '%' . $q . '%')->get();
            $fixKeys = Fixedkey::where('name', 'LIKE', '%' . $q . '%')->get();
            $admins = Admins::where('name', 'LIKE', '%' . $q . '%')->get();
            $fixed = FixedModel::where('title', 'LIKE', '%' . $q . '%')->get();
            $recciring = reccuring::where('title', 'LIKE', '%' . $q . '%')->get();



            $results = [
                'categories' => $categories,
                'fixKeys' => $fixKeys,
                'admins' => $admins,
                'Fixed' => $fixed,
                'Recciring' => $recciring,
            ];

            return response()->json(['results' => $results]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Failed to Find the {$q}.\n' . $th,
            ], 500);
        }
    }
    public function IncExp()
    {
        try {
            $recurringIncomes = Reccuring::where('type', 'inc')->with('category')->get();
            $recurringExpenses = Reccuring::where('type', 'exp')->with('category')->get();
            $fixedIncomes = FixedModel::where('type', 'inc')
            ->where('s_paid', true)
            ->with('category', 'fixedkey')
            ->get();         
            $fixedExpenses = FixedModel::where('type', 'exp')
            ->where('s_paid', true)
            ->with('category', 'fixedkey')
            ->get();
            $recurringTotalIncome = $recurringIncomes->sum('amount');
            $recurringTotalExpense = $recurringExpenses->sum('amount');
            $fixedTotalIncome = $fixedIncomes->sum('amount');
            $fixedTotalExpense = $fixedExpenses->sum('amount');

            $totalIncome = $recurringTotalIncome + $fixedTotalIncome;
            $totalExpense = $recurringTotalExpense + $fixedTotalExpense;
            $totalProfit = $totalIncome - $totalExpense;

            return response()->json([
                'Recurring' => [
                    'Incomes' => [
                        'Total-Amount' => $recurringTotalIncome,
                        'data' => $recurringIncomes,
                    ],
                    'Expenses' => [
                        'Total-Amount' => $recurringTotalExpense,
                        'data' => $recurringExpenses,
                    ],
                ],
                'Fixed' => [
                    'Incomes' => [
                        'Total-Amount' => $fixedTotalIncome,
                        'data' => $fixedIncomes,
                    ],
                    'Expenses' => [
                        'Total-Amount' => $fixedTotalExpense,
                        'data' => $fixedExpenses,
                    ],
                ],
                'Total' => [
                    'Income' => $totalIncome,
                    'Expense' => $totalExpense,
                    'Profit' => $totalProfit,
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Failed to fetch income and expense data. Please try again later.\n' . $th,
            ], 500);
        }
    }


    public function checkProfitGoal(Request $request)
    {
        try {
            if ($request->has('title')) {
                $input = $request->input('title');
                $profitGoal = profit_goals::where('title', $input)->first();
            } else {
                throw new \InvalidArgumentException('Title is required');
            }
    
            if (!$profitGoal) {
                return response()->json([
                    'message' => 'Profit goal for the current Title not set',
                ], 404);
            }
    
            $recurringIncomes = Reccuring::where('type', 'inc')->sum('amount');
            $recurringExpenses = Reccuring::where('type', 'exp')->sum('amount');
            $fixedIncomes = FixedModel::where('type', 'inc')->sum('amount');
            $fixedExpenses = FixedModel::where('type', 'exp')->sum('amount');
            $totalIncome = $recurringIncomes + $fixedIncomes;
            $totalExpense = $recurringExpenses + $fixedExpenses;
            $currentProfit = $totalIncome - $totalExpense;
    
            if ($profitGoal->goal <= 0) {
                return response()->json([
                    'message' => 'Profit goal must be greater than zero',
                ], 400);
            }
    
            $progression = ($currentProfit * 100) / $profitGoal->goal;
    
            if ($currentProfit >= $profitGoal->goal) {
                return response()->json([
                    'message' => 'Profit goal achieved!',
                    'current_profit' => $currentProfit,
                    'profit_goal' => $profitGoal->goal,
                    'progression' => 100,
                ], 200);
            }
    
            if ($profitGoal->end_date >= time()) {
                return response()->json([
                    'message' => 'Profit in progress! Keep going!',
                    'current_profit' => $currentProfit,
                    'profit_goal' => $profitGoal->goal,
                    'progression' => max($progression, 0) . '%',
                ], 200);
            }
    
            return response()->json([
                'message' => 'Profit goal not achieved :\'(',
                'current_profit' => $currentProfit,
                'profit_goal' => $profitGoal->goal,
                'progression' => $progression.'%',
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An error occurred while checking the profit goal',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    
}