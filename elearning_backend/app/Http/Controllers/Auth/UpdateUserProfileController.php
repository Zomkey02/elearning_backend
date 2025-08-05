<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();

         $credentials = $request->validate([
            'username' => ['sometimes', 'string', 'min:2', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['sometimes', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['sometimes', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'current_password' => ['required', 'string'],
        ]);

        if (!Hash::check($credentials['current_password'], $user->password)) {
            return response()->json(['message' => 'Invalid current password.'], 422);
        }

        if (isset($credentials['username'])) {
            $user->username = $credentials['username'];
        }

        if (isset($credentials['email'])) {
            $user->email = $credentials['email'];
        }

        if (isset($credentials['password'])) {
            $user->password = Hash::make($credentials['password']);
        }

        $user->save();

        return response()->json(['message' => 'Profile updated successfully.','user' => $user]);
    }
}
