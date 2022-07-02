<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller{

    /**
     * @param RegisterRequest $request
     * @return User|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse
     */
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

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
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
