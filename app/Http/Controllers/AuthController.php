<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller{

    public function register(RegisterRequest $request){
        $paramsNeeded = Config::get('auth.needed_params');

        if ($request->safe()->only($paramsNeeded)){
            // Registering User
            try {
                return User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password'))
                ]);
            }catch (\Exception $e){
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }


        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials'
        ]);

    }

    public function login(LoginRequest $request){
        if (!Auth::attempt($request->only('email','password'))){
            return response([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ],Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt',$token,60 * 24);

        return response([
            'status' => 'success'
        ])->withCookie($cookie);
    }

}
