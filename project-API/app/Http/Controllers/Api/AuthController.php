<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /* 
        auth:sanctum

        كتتأكد:

        واش user راه داير login؟

        كتجيب:

        auth()->user()

        🛡️ Policy

        كتسول:

        واش هاد user يقدر يدير هاد action؟

        📌 Policy ما تخدمش بلا auth
        حيت خاصها user معروف.
    */
    public function login(Request $request) 
    {
        //dd('controller reached');
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);
        $user = User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'invalid account',
            ], 401);
        }
        $user->tokens()->delete();
        $token = $user->createToken('user-token')->plainTextToken;

        return response()->json([
            'message' => ' Login Successfully',
            'token' => $token,
            'user' => ['user_id' => $user->id, 'name' => $user->name, 'role' => $user->role]
        ], 200);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out Successfully',
        ]);
    }
    public function register(Request $request)
    {
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'cin' => 'required|string|unique:users,cin',
            'email' => 'required|string|email|unique:users,email',
            'phone' => ['required', 'regex:/^0[0-9]{9}$/' ,'string', 'unique:users,phone'],
            //'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2040',
            'description' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);
        /*  if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('users','public');
            }else{
                $data['image'] = null;
            }
        }*/
        $data['password'] = Hash::make($data['password']);  //$data['password'] = Hash::make($request->password);
        $data['role'] = 'employee';
        $user = User::create($data);
        $token = $user->createToken('user-token')->plainTextToken;
        return response()->json([
            'status' => 200,
            'message' => 'Account Created Successfully',
            'token' => $token,
            'user' => [
                'name' => $user->name,
                'role' => $user->role,
                'email' => $user->email
            ]
        ], 200);
    }
    /*public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'cin' => 'required|string|unique:users,cin',
            'email' => 'required|string|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
            //'image' => 'nullable|image|mimes:jpg,jpeg,png',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('users', 'public');
        } else {
            $data['image'] = null;
        }

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $token = $user->createToken('user-token')->plainTextToken;

        return response()->json([
            'message' => 'Account created successfully',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role
            ]
        ], 201);
    }*/
}
