<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Admins;
use Storage;

class adminsController extends Controller
{
    public function Getadmins(Request $Request)
    {
        try {
            $admin = Admins::paginate(10); //all()
        
            if ($admin->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No Admin found',
                ], 404);
            }
        
            return response()->json([
                'status' => true,
                'data' => $admin,
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while retrieving admins',
                'error' => $err->getMessage(),
            ], 500);
        }
        
    }
public function GetAdminByName(Request $request){
    try {
        $name = request('name'); 
    
        $admin = Admins::where(function ($query) use ($name) {
                if ($name) {
                    $query->where('name', 'LIKE', '%'.$name.'%');
                }
                
            })
            ->paginate(10);
    
        if ($admin->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No admin found with the given search criteria',
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
  
    public function Postadmins(Request $Request)
    {
        try {

            $validator = Validator::make($Request->all(), [
                'name' => 'required|string',
                'email' => 'required|email|unique:admins,email,',
                'password' => 'required|string|min:8',
                'is_super' => 'sometimes|required|boolean',
                'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'tel'=>'required|string|'

            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }
            $admin = new Admins;
            $name = $Request->input("name");
            $email = $Request->input("email");
            $tel = $Request->input("tel");
            $password = hash::Make($Request->input("password"));
            if ($Request->has('is_super')) {
                $admin->is_super = $Request->input('is_super');
            }
            else{
                $admin->is_super = 0;
            }
            if ($Request->hasFile('image')) {
                $image = $Request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('public/images', $filename);
                $admin->image = $filename;
            }

            $admin->name = $name;
            $admin->email = $email;
            $admin->tel = $tel;
            $admin->password = $password;

            $admin->save();
            $admin->fresh();

            return response()->json([
                "message" => "Admin created",
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while updating Admin',
                'error' => $err->getMessage(),
            ], 500);
        }
    }

    public function Deleteadmins(Request $request, $id)
    {

        try {
            $admins = Admins::find($id);
            $admins->delete();
            return response()->json([
                'message' => 'admins deleted Successfully!',
            ], 200);

        } catch (\Exception $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while updating Admins!',
                'error' => $err->getMessage(),
            ], 500);
        }
    }


    public function Editadmins(Request $request, $id)
    {
        try {
            $admins = Admins::find($id);



            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:admins',
                'password' => 'sometimes|required|string|min:8|confirmed',
                'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_super' => 'sometimes|required|boolean',
                'tel'=>'sometimes|required|string|'


            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $inputs = [];
            if ($request->has('name')) {
                $inputs['name'] = $request->input('name');
            }if ($request->has('is_super')) {
                $inputs['is_super'] = $request->input('is_super');
            }
            if ($request->has('email')) {
                $inputs['email'] = $request->input('email');
            }
             if ($request->has('tel')) {
                $inputs['tel'] = $request->input('tel');
            }
            if ($request->has('password')) {
                $password = hash::Make($request->input("password"));
                $inputs['password'] = $password;
            }
            if ($request->hasFile('image')) {

                $pastImage = $admins->image;
                if ($pastImage != null) {
                    Storage::delete('public/images/' . $pastImage);
                }
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('public/images', $filename);
                $inputs['image'] = $filename;
            }
            DB::transaction(function () use ($admins, $inputs) {
                $admins->update($inputs);
                $admins->refresh();
            });
            
            return response()->json([
                'message' => 'admins edited successfully!',
                'data' => $admins,
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while updating Admin',
                'error' => $err->getMessage(),
            ], 500);
        }
    }


 
}