<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DeleteUserController extends Controller
{
    public function __invoke (Request $request)
    {
        $user = $request->user();

         // Validate password input
        $credentials = $request->validate([
            'password' => ['required', 'string'],
        ]);

        // Check if password matches
        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid password. Account not deleted.',
            ], 422);
        }

        // Revoke all of the user's tokens
        $user->tokens()->delete();

        // Delete the user
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);

    }
}
