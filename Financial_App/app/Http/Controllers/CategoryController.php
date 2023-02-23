<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store (Request $request){

    $request->validate([
    //   'id'=>'required|integer',
      'name'=>'required|string|max:255',
      ]);

    //   $category = Category::class($data); 
    $category = new Category;

    $category->name= $request->name;

    $category->save();




    

      return response()->json([
        'status'=> true,
        'message'=> 'Category created.',
        'data' => $category,
        
      ],201,); //add status to know that "is created"
    }
}
