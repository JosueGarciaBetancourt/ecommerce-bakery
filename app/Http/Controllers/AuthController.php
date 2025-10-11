<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\user\LoginRequest;
use App\Http\Requests\user\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            // Validación automática por RegisterRequest
            $data = $request->validated();

            // Crear el nuevo usuario
            $createdUser = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            return response()->json([
                'message' => 'Usuario creado correctamente',
                'createdUser' => $createdUser
            ], 201);  // Código de estado 201 para "creado correctamente"
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar el usuario: ' . $e->getMessage()
            ], 500);  // Código de estado 500 para "Error interno del servidor"
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            // Validación automática por LoginRequest
            $data = $request->validated();

            // Buscar al usuario por su email
            $user = User::where('email', $data['email'])->first();

            // Verificar si el usuario existe y si la contraseña es correcta
            if (!$user || !Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'message' => 'Credenciales inválidas'
                ], 401);  // Código de estado 401 para "No autorizado"
            }

            // Crear el token de acceso
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200);  // Código de estado 200 para "OK"
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error en el login: ' . $e->getMessage()
            ], 500);  // Código de estado 500 para "Error interno del servidor"
        }
    }

    public function logout(Request $request)
    {
        try {
            // Eliminar el token actual para cerrar sesión
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Sesión cerrada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cerrar la sesión: ' . $e->getMessage()
            ], 500);
        }
    }
}