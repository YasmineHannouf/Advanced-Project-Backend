<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class CategoryController extends Controller
{


  public function store(Request $request)
  {
    try {
      $request->validate([

        'name' => 'required|string|max:255',
      ]);

      $category = new Category;
      $category->name = $request->name;
      $category->save();

   return response()->json([
        'status' => true,
        'message' => 'Category created.',
        'data' => $category,

      ], 201, );
    } catch (\Throwable $th) {
      return response()->json([
        'status' => false,
        'Error' => $th->getMessage()

      ], 500);
    }
  }



  public function edit(Request $request, $id)
  {

    try {
      $updateFields = [];
      $updateFields['name'] = $request->input('name');
      $category = Category::findorFail($id);
      $category->update($updateFields);
      echo $category;
      $updateFields = $category->fresh();
      return response()->json([

        'status' => true,
        'Categories' => $updateFields

      ], 201);

    } catch (\Throwable $th) {
      return response()->json([
        'status' => false,
        'Error' => $th->getMessage()

      ], 500);
    }

  }

  public function delete(Request $request, $id)
  {

    try {
      $category = Category::findorFail($id);
      $category->delete();
      return response()->json(['Status' => true, 'Message' => 'Deleted Successfully'], 200);
    } catch (\Throwable $th) {
      return response()->json([
        'status' => false,
        'Error' => $th->getMessage()

      ], 500);
    }

  }

  public function sortByNameDesc()
  {
    try {
      $nameByDesc = Category::orderBy('name', 'desc')->get();
      return response()->json(['Status' => true, 'CategoryByDesc' => $nameByDesc], 200);

    } catch (\Throwable $th) {
      return response()->json([
        'status' => false,
        'Error' => $th->getMessage()

      ], 500);
    }
  }

  public function sortByNameAsc()
  {
    try {
      $nameByDesc = Category::orderBy('name', 'Asc')->get();
      return response()->json(['Status' => true, 'CategoryByDesc' => $nameByDesc], 200);

    } catch (\Throwable $th) {
      return response()->json([
        'status' => false,
        'Error' => $th->getMessage()

      ], 500);
    }
  }

  public function show(Request $request)
  {
    try {
        $pageNumber = $request->query("page");
        $perPage = $request->query("per_page");

        if ($pageNumber) {
            $category = Category::paginate($perPage || 10, ['*'], 'page', $pageNumber);
        } else {
            $category = Category::all();
        }

        return response()->json([
            'status' => true,
            'message' => 'Categories retrieved successfully',
            'data' => $category,
        ], 200);
    } catch (\Throwable $th) {
      return response()->json([
        'status' => false,
        'Error' => $th->getMessage()

      ], 500);
    }
  }
  public function GetCategoriesByName(Request $request){
    try {
        $name = request('name'); 
    
        $admin = Category::where(function ($query) use ($name) {
                if ($name) {
                    $query->where('name', 'LIKE', '%'.$name.'%');
                }
                
            })
            ->paginate(10);
    
        if ($admin->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No Categories found with the given search criteria',
            ], 404);
        }
    
        return response()->json([
            'status' => true,
            'message' => $admin,
        ], 200);
    } catch (\Exception $err) {
        return response()->json([
            'status' => false,
            'message' => 'Error while searching for admins',
            'error' => $err->getMessage(),
        ], 500);
    }
    
    
}
  public function showPcategory()
  {
      try {



              $category = Category::paginate(10);


          return response()->json([
              'status' => true,
              'message' => 'Categories retrieved successfully',
              'data' => $category,
          ], 200);
      } catch (\Throwable $th) {
          return response()->json([
              'status' => false,
              'message' => 'Error retrieving categories',
              'error' => $th->getMessage(),
          ], 500);
      }
  }
}
