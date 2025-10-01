<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Barber; // Ensure you import the Barber model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Import Hash facade for password hashing

class AuthController extends Controller
{
    // Register a new user
   public function register(Request $request)
{
    // Validate input directly in the controller like the first function
    $attributes = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'phone'    => 'nullable|string|max:20',
        'role'     => 'nullable|string|in:customer,barber',
    ]);

    // Hash the password before saving
    $attributes['password'] = Hash::make($attributes['password']);

    // Set default role if not provided
    $attributes['role'] = $attributes['role'] ?? 'customer';

    // Create the user
    $user = User::create($attributes);

    // If the user is a barber, create an associated Barber record
    if ($user->role === 'barber') {
        Barber::create(['user_id' => $user->id]);
    }

    // Create a token for the user
    $token = $user->createToken('token')->plainTextToken;

    // Return response similar to the first function
    return response()->json([
        'message' => 'User registered successfully',
        'user'    => $user,
        'token'   => $token,
    ], 201);
}

    // Login user and issue API token
    public function login(Request $request)
    {
        // Validate the login fields
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Check if credentials are valid
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Get authenticated user
            $user = Auth::user();

            // Generate API token for the authenticated user
            $token = $user->createToken('YourAppName')->plainTextToken;

            // Return the user data and token in response
            return response()->json([
                'user'  => $user,
                'token' => $token,
            ]);
        }

        // If authentication fails, return an error response
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
