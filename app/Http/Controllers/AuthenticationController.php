<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{

    function register(Request $request) {
        $validator = Validator::make($request->all(), [
           'name' => 'required|string|max:255',
           'email' => 'required|string|max:255|unique:users',
           'password' => 'required|string|min:8'
        ]);

        // dd($validator->errors());
        if ($validator->fails())
        {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name'=>$request->name,
            'email'=> $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
           'data' => $user,
           'access_token' => $token,
           'token_type' => 'Bearer'
        ]);
    }

    function login(Request $request) {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password))
        {
            throw ValidationException::withMessages([
                'email'=>['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'=>'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }    

    function logout() {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'logout success'
        ]);
    }
}
