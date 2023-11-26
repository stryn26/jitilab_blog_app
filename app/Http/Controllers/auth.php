<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class auth extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login', 'register']]);
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'email' => $request->email,
        ]);

        return response()->json([
            'message'=>'User Berhasil Dibuat',
            'data'=>$user
        ]);
    }

    public function login(Request $request){
        if(!FacadesAuth::attempt($request->only('email','password'))){
            return response()->json([
                'message' => 'Invalid login'
            ], 401);
        }

        $user = FacadesAuth::user();
        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $request->user()->createToken('ApiToken')->plainTextToken,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => FacadesAuth::user(),
            'authorisation' => [
                'token' => FacadesAuth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
