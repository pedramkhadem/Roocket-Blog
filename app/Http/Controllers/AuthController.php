<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**handle login user */
    public function login(LoginRequest $request )
    {
        $request->safe()->all();

        $user =User::where('email' , $request->email)->first();

        if(!$user || !Hash::check($request->password , $user->password)){
            return response()->json([
                'message'=>'your password or email was incorrect ! ',
            ]);
        }

        return response()->json([
            'status'=>True,
            'message' => 'Login Successfuly',
            'user'=>$user,
            'token'=>$user->createToken('API TOKEN')->plainTextToken,
        ]);
    }
    /**Create new user  */
    public function register(RegisterRequest $request)
    {
        $request->safe()->all();
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=> Hash::make($request->password),
        ]);

       return response()->json([
        'status'=>True,
        'message' => 'User Created Successfully',
        'user'=>$user,
       ]);

    }

    /**Logout the user  */
    public function logout(FormRequest $request)
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'status'=>True,
            'message' => 'User Successfully logged out',
        ]);
    }


}
