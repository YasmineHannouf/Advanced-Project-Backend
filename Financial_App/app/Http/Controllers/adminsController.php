<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\admins;
class adminsController extends Controller
{
   public function Getadmins(Request $Request){
$admin=admins::get();
return response()->json([
    "message"=>$admin,
]);
   }

   public function Postadmins (Request $Request){
    $admin = new admins;
    $name = $Request -> input("name");
    $email = $Request -> input("email");
    $password = hash::Make($Request -> input("password"));

    $admin -> name = $name;
    $admin -> email = $email;
    $admin -> password = $password;

    $admin -> save();

    return response()->json([
        "message"=>"Admin created",
    ]);
   }

   public function Deleteadmins(Request $request, $id){

    $admins = admins::find($id);
    $admins->delete();
    return response()->json([
        'message' => 'admins deleted Successfully!',
    ]);
}


public function Editadmins(Request $request, $id){
    $admins =  admins::find($id);
    $inputs= $request->all();
    $admins->update($inputs);
    return response()->json([
        'message' => 'admins edited successfully!',
        'admins' => $admins,
    ]);
}

}
