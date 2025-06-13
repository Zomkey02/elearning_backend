<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    
    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email','max:255'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }

        $user = User::where('email', $request->input('email'))->firstOrFail();
        
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ], 200); 

        //dd(env('SANCTUM_STATEFUL_DOMAINS'));
        
        //$request->session()->regenerate();

    }
}
