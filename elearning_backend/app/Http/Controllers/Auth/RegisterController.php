<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
//use Laravel\Fortify\Rules\Password as PasswordRule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash, Auth;

class RegisterController extends Controller
{
    public function __invoke(Request $request) 
    {
        $request->validate([
            'username' => ['required', 'string', 'min:2', 'max:255', Rule::unique(User::class)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // default role set to user
        ]);

        /* Auth::login($user);
        $request->session()->regenerate(); */
        
        return response()->json([
            'message' => 'User registered successfully.',
            'user' => $user
        ], 201);

    }
}
