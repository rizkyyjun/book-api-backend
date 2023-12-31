<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Info(
 *     title="Basic Book API",
 *     version="1.0.0",
 *     description="API documentation for the Basic Book API",
 *     @OA\Contact(
 *         email="rizkyjuniastiar37@gmail.com",
 *         name="Rizky Juniastiar"
 *     ),
 *     @OA\License(
 *         name="MIT License",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 */
class AuthenticationController extends Controller
{

    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"Authentication"},
     *     summary="User registration",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string", format="email"),
     *                 @OA\Property(property="password", type="string", format="password"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="201", description="Successfully registered"),
     *     @OA\Response(response="422", description="Validation error"),
     * )
     */
    function register(Request $request) {
        $validator = Validator::make($request->all(), [
           'name' => 'required|string|max:255',
           'email' => 'required|string|max:255|unique:users',
           'password' => 'required|string|min:8'
        ]);

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

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Authentication"},
     *     summary="User login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string", format="email"),
     *                 @OA\Property(property="password", type="string", format="password"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Successfully logged in"),
     *     @OA\Response(response="401", description="Invalid credentials"),
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Authentication"},
     *     summary="User logout",
     *     security={{ "sanctum": {} }},
     *     @OA\Response(response="200", description="Successfully logged out"),
     * )
     */
    function logout() {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'logout success'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/user",
     *     tags={"User"},
     *     summary="Get authenticated user details",
     *     security={{ "sanctum": {} }},
     *     @OA\Response(response="200", description="User details"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     * )
     */
    function index() {
        $user = Auth::user();
        return response()->json($user);
    }
}
