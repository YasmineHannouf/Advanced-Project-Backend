<?php
namespace App\Http\Controllers;

use App\Models\Fixedkey as Fixedkeys;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class Fixedkey extends Controller
{
    public function show()
  {
    try {
      $nameByDesc = Fixedkeys::get();
      return response()->json(['Status' => true, 'Fixed_keysByDesc' => $nameByDesc], 200);

    } catch (\Throwable $th) {
      return response()->json([
        'status' => false,
        'Error' => $th->getMessage()

      ], 500);
    }
  }

  public function store(Request $request)
  {
      try {
          $request->validate([
              'name' => 'required|string|max:255',
              'is_active' => 'boolean',
          ]);
         
          $Fixed_keys = new Fixedkeys;
          $Fixed_keys->name = $request->name;
          $is_active = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);
          $Fixed_keys->is_active = $is_active;
          $Fixed_keys->save();
  
          return response()->json([
              'status' => true,
              'message' => 'Fixed_keys created.',
              'data' => $Fixed_keys,
          ], 201);
      } catch (\Throwable $th) {
          return response()->json([
              'status' => false,
              'Error' => $th->getMessage()
          ], 500);
      }
  }
  //  public function store(Request $request)
  // {
  //   try {
  //     $request->validate([

  //       'name' => 'required|string|max:255',
  //       'is_active' => 'boolean|',
  //     ]);
   
  //     $Fixed_keys = new Fixedkeys;
  //     $Fixed_keys->name = $request->name;
  //     if ($request->has('is_active')) {
  //       $Fixed_keys['is_active'] = $request->input('is_active');
  //   }
  //     else $Fixed_keys->is_active = 1;
  //     $Fixed_keys->save();

  //  return response()->json([
  //       'status' => true,
  //       'message' => 'Fixed_keys created.',
  //       'data' => $Fixed_keys,

  //     ], 201, );
  //   } catch (\Throwable $th) {
  //     return response()->json([
  //       'status' => false,
  //       'Error' => $th->getMessage()

  //     ], 500);
  //   }
  // }



  public function edit(Request $request, $id)
  {

    try {
      $updateFields = [];
      $updateFields['name'] = $request->input('name');
      $updateFields['is_active'] = $request->input('is_active');
      $Fixed_keys = Fixedkeys::findorFail($id);
      $Fixed_keys->update($updateFields);
      echo $Fixed_keys;
      $updateFields = $Fixed_keys->fresh();
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
      $Fixed_keys = Fixedkeys::findorFail($id);
      $Fixed_keys->delete();
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
      $nameByDesc = Fixedkeys::orderBy('name', 'desc')->get();
      return response()->json(['Status' => true, 'Fixed_keysByDesc' => $nameByDesc], 200);

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
      $nameByDesc = Fixedkeys::orderBy('name', 'Asc')->get();
      return response()->json(['Status' => true, 'Fixed_keysByDesc' => $nameByDesc], 200);

    } catch (\Throwable $th) {
      return response()->json([
        'status' => false,
        'Error' => $th->getMessage()

      ], 500);
    }
  }
}
