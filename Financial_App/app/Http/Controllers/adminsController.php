<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\admins;
use Storage;

class adminsController extends Controller
{
    public function Getadmins(Request $Request)
    {
        try {
            $admin = Admins::get();
            return response()->json([
                "message" => $admin,
            ], 200);
        } catch (\Exception $err) {
            return response()->json([
                'status' => false,
                'message' => 'Error while updating income',
                'error' => $err->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response(['message' => 'Invalid credentials!' . $request->password], Response::HTTP_UNAUTHORIZED);
        }

        $admin = Auth::user();
        $token = $admin->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24);

        return response(['message' => 'success', 'admin' => $admin], 200)->withCookie($cookie);

    }

    public function Postadmins(Request $Request)
    {
        try {

            $validator = Validator::make($Request->all(), [
                'name' => 'required|string',
                'email' => 'required|email|unique:admins,email,',
                'password' => 'required|string|min:8',
                'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048'

            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }
            $admin = new admins;
            $name = $Request->input("name");
            $email = $Request->input("email");
            $password = hash::Make($Request->input("password"));
            if ($Request->hasFile('image')) {
                $image = $Request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('public/images', $filename);
                $admin->image = $filename;
            }

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
            $admins = Admins::find($id);
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
            $admins = Admins::find($id);



            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:admins',
                'password' => 'sometimes|required|string|min:8|confirmed',
                'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048'

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
            }
            if ($request->has('email')) {
                $inputs['email'] = $request->input('email');
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


    public function logout()
    {
        $cookie = Cookie::forget('jwt');
        return response(['Message' => "Good Bye"])->withCookie($cookie);
    }
}
