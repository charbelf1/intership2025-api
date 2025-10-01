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
   public function register(RegisterRequest $request)
    {
        // Validate and get the input data
        $data = $request->validated();

        // Hash the password before saving it
        $data['password'] = Hash::make($data['password']); // Hash the password

        // Create a new user
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'role'     => $data['role'] ?? 'customer',
            'password' => $data['password'],
        ]);

        // If the user is a barber, create an associated Barber record
        if ($user->role === 'barber') {
            Barber::create(['user_id' => $user->id]);
        }

        // Create and return the API token for the user
        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
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
