<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('name', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => 401,
                    'status_code' => 'error',
                    'message' => 'Invalid credentials'
                ], 401);
            }

            return response()->json([
                'status' => 200,
                'status_code' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'user' => JWTAuth::user()
                ]
            ])->cookie('token', $token, 60, '/', null, true, true, false, 'None');
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 500,
                'status_code' => 'error',
                'message' => 'Login failed',
                'data' => [
                    'error' => $e->getMessage()
                ]
            ], 500);
        }
    }

    public function logout()
    {
        try {
            // auth()->logout();
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'status' => 200,
                'status_code' => 'success',
                'message' => 'Logged out successfully'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 500,
                'status_code' => 'error',
                'message' => 'Logout failed',
                'data' => [
                    'error' => $e->getMessage()
                ]
            ]);
        }
    }

    public function guestAccess()
    {
        $guestUser = User::where('name', 'guest')->first();

        if (!$guestUser) {
            // Log::warning('Guest user not found in database');
            return response()->json(['error' => 'Guest user not found'], 404);
        }

        // Log::info('Guest user found, generating token for user ID: ' . $guestUser->id);
        $guest_token = auth('api')->login($guestUser);
        // Log::info('Token generated successfully for guest user');

        return response()->json(['guest_token' => $guest_token]);
    }
}
