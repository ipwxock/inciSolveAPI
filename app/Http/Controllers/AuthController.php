<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController
{
    // public function login(Request $request)
    // {
    //     $credentials = $request->only(['dni', 'password']); // Toma solo los campos necesarios
    //     $validated = $request->validate([
    //         'dni' => 'required|regex:/^[0-9]{8}[A-Za-z]$/',
    //         'password' => 'required',
    //     ]);

    //     if (Auth::attempt($credentials)) {
    //         $user = Auth::user();
    //         if ($user instanceof \App\Models\User) {
    //             $deviceName = $request->input('device_name', 'default_device');
    //             $token = $user->createToken($deviceName)->plainTextToken;

    //         } else {
    //             return response()->json(['message' => 'Invalid credentials'], 401);
    //         }

    //         return response()->json([
    //             'access_token' => $token,
    //             'token_type' => 'Bearer',
    //         ]);
    //     }

    //     return response()->json(['message' => 'Invalid credentials'], 401);
    // }


    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        // Log::info('Credenciales recibidas:', $credentials);

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $user = Auth::user();

        // Log::info('User', ['user' => $user]);

        if ($user instanceof \App\Models\User) {
            $deviceName = $request->input('device_name', 'default_device');

            $expiresAt = (new DateTime())->modify('+7 days');

            $token = $user->createToken($deviceName,['*'], $expiresAt)->plainTextToken;

            // Log::info('Token creado correctamente', ['token' => $token]);

        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => $user->role,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function user(Request $request)
    {
        return $request->user();
    }
}