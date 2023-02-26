<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\admins;

class adminsController extends Controller
{
    public function Getadmins(Request $Request)
    {
        try {
            $admin = admins::get();
            return response()->json([
                "message" => $admin,
            ],200);
        } catch (\Exception $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while updating income',
                'error' => $err->getMessage(),
            ], 500);
        }
    }

    public function Postadmins(Request $Request)
    {
        try {

            $Request->validate([
                'name' => 'nullable|string',
                'email' => 'nullable|email|unique:admins,email,',
                'password' => 'nullable|string|min:8',
            ]);

            $admin = new admins;
            $name = $Request->input("name");
            $email = $Request->input("email");
            $password = hash::Make($Request->input("password"));

            $admin->name = $name;
            $admin->email = $email;
            $admin->password = $password;

            $admin->save();

            return response()->json([
                "message" => "Admin created",
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while updating income',
                'error' => $err->getMessage(),
            ], 500);
        }
    }

    public function Deleteadmins(Request $request, $id)
    {

        try {
            $admins = admins::find($id);
            $admins->delete();
            return response()->json([
                'message' => 'admins deleted Successfully!',
            ], 200);

        } catch (\Exception $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while updating income',
                'error' => $err->getMessage(),
            ], 500);
        }
    }


    public function Editadmins(Request $request, $id)
    {
        try {
            $admins = admins::find($id);

            $request->validate([
                'name' => 'nullable|string',
                'email' => 'nullable|email|unique:admins,email,',
                'password' => 'nullable|string|min:8',
            ]);

            $inputs = [];
            if ($request->has('name')) {
                $inputs['name'] = $request->input('name');
            }
            if ($request->has('email')) {
                $inputs['email'] = $request->input('email');
            }
            if ($request->has('password')) {
                $password = hash::Make($request->input("password"));
                $inputs['password'] = $password;
            }

            $admins->update($inputs);
            return response()->json([
                'message' => 'admins edited successfully!',
                'admins' => $admins,
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while updating income',
                'error' => $err->getMessage(),
            ], 500);
        }
    }


}