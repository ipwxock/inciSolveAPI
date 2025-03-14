<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController
{

    /**
     * Inicia sesión con las credenciales proporcionadas y devuelve un token de acceso si son correctas.
     *
     * @param Request $request Contiene el email y la contraseña del usuario.
     * @return \Illuminate\Http\JsonResponse Devuelve el token de acceso y la información del usuario si la autenticación es exitosa, o un mensaje de error si falla.
     */
    public function login(Request $request)
    {
        try{
            $credentials = $request->only(['email', 'password']);

            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Credenciales incorrectas'], 401);
            }

            $user = Auth::user();

            if ($user instanceof \App\Models\User) {
                $deviceName = $request->input('device_name', 'default_device');

                $expiresAt = (new DateTime())->modify('+7 days');

                $token = $user->createToken($deviceName,['*'], $expiresAt)->plainTextToken;

                // Log::info('Token creado correctamente', ['token' => $token]);

            } else {
                return response()->json(['message' => 'Credenciales incorrectas'], 401);
            }

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'role' => $user->role,
                'username' => $user->first_name . ' ' . $user->last_name,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $e->errors()
            ], 422);
        }

    }

    /**
     * Cierra la sesión del usuario eliminando todos sus tokens de autenticación.
     *
     * @param Request $request Contiene la información del usuario autenticado.
     * @return \Illuminate\Http\JsonResponse Devuelve un mensaje confirmando la desconexión.
     */
    public function logout(Request $request)
    {
        // Elimina los tokens de autenticación del usuario
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        // Opcionalmente: invalida la sesión del usuario si estás usando sesiones
        // $request->session()->invalidate();

        return response()->json(['message' => 'Logged out'], 200);
    }

    public function roleCheck(Request $request)
    {
        return response()->json(['role' => $request->user()->role]);
    }

    /**
     * Devuelve el rol del usuario autenticado.
     *
     * @param Request $request Contiene la información del usuario autenticado.
     * @return \Illuminate\Http\JsonResponse Devuelve el rol del usuario.
     */
    public function user(Request $request)
    {
        return $request->user();
    }

    /**
     * Devuelve la información del usuario autenticado.
     *
     * @param Request $request Contiene la información del usuario autenticado.
     * @return \Illuminate\Foundation\Auth\User El usuario autenticado.
     */
    public static function isAuthenticated()
    {
        return Auth::check();
    }

    /**
     * Redirige al usuario si no está autenticado.
     *
     * @param Request $request Contiene la solicitud HTTP.
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Lanza un error 401 si la solicitud no espera una respuesta JSON.
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            abort(401, 'No autenticado.');
        }
    }
}
