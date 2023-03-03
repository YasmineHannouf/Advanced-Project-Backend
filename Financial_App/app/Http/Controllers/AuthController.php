<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cookie;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function user(){
        return Auth::user();
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response(['message' => 'Invalid credentials!'], Response::HTTP_UNAUTHORIZED);
        }

        $admin = Auth::user();
        $token = $admin->createToken('token')->plainTextToken;
        $cookie = cookie('Authorisation', $token, 60 * 24);

        return response(['message' => 'success', 'admin' => $admin], 200)->withCookie($cookie);

    }
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function logout()
    {
        {
            $cookie = Cookie::forget('Authorisation');
            return response(['Message' => "Good Bye"])->withCookie($cookie);
        }
        
    }
}
