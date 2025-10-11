<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\user\CreateUserRequest;
use App\Http\Requests\user\UpdateUserRequest;

class UserController extends Controller
{
    public function getUsers()
    {
        try {
            $users = User::all();

            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Error al obtener usuarios. " . $e->getMessage()
            ], 500); 
        }
    }

    public function getUser($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Error al obtener el usuario con ID $id. " . $e->getMessage()
            ], 500); 
        }
    }

    public function getAuthenticatedUser()
    {
        try {
            $user = Auth::user();
            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Error al obtener el usuario autenticado. " . $e->getMessage()
            ], 500); 
        }
    }

    public function getTrashedUsers() {
        try {
            $users = User::onlyTrashed()->get();

            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Error al obtener los usuarios deshabilitados. " . $e->getMessage()
            ], 500);  // Código de estado 500 para "Error interno del servidor"
        }
    }

    public function getTrashedUser($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Error al obtener el usuario desahilitado con ID $id. " . $e->getMessage()
            ], 500);  // Código de estado 500 para "Error interno del servidor"
        }
    }

    public function createUser(CreateUserRequest $request)
    {
        try {
            // Validación automática por CreateUserRequest
            $data = $request->validated();

             // Crear el nuevo usuario
             User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'DNI' => $data['DNI'] ?? null,
                'personal_name' => $data['personal_name'] ?? null,
                'cargo' => $data['cargo'] ?? null,
                'corporative_email' => $data['corporative_email'] ?? null,
            ]);

            return response()->json([
                'message' => 'Usuario creado correctamente'
            ], 201);  // Código de estado 201 para "creado correctamente"
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Error al crear el usuario. " . $e->getMessage()
            ], 500);  // Código de estado 500 para "Error interno del servidor"
        }
    }

    public function updateUser(UpdateUserRequest $request, $id)
    {
        try {
            // Validación automática por UpdateUserRequest
            $data = $request->validated();

            // Buscar al usuario por su ID
            $user = User::findOrFail($id);

            // Actualizar solo los campos presentes
            if (isset($data['name'])) $user->name = $data['name'];
            if (isset($data['email'])) $user->email = $data['email'];
            if (isset($data['password'])) $user->password = Hash::make($data['password']);

            $user->save();

            return response()->json([
                'message' => 'Usuario actualizado correctamente'
            ], 200);  
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Error al actualizar el usuario con ID $id. " . $e->getMessage()
            ], 500);
        }
    }

    public function disableUser($id)
    {
        try {
            $user = User::findOrFail($id); // Solo usuarios activos (no eliminados lógicamente)
    
            // Prevenir deshabilitar al usuario admin (ID 1)
            if ($user->id === 1) {
                return response()->json([
                    'message' => 'El usuario admin no puede ser deshabilitado'
                ], 403); // 403: Prohibido
            }
    
            $user->delete(); // Soft delete
    
            return response()->json([
                'message' => 'Usuario deshabilitado correctamente'
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Error al deshabilitar el usuario con ID $id. " . $e->getMessage()
            ], 500);
        }
    }

    public function enableUser($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id); // Buscar usuarios eliminados lógicamente
          
            $user->restore();
          
            return response()->json([
                'message' => 'Usuario habilitado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Error al habilitar el usuario con ID $id. " . $e->getMessage()
            ], 500);  // Código de estado 500 para "Error interno del servidor"
        }
    }
    
    public function deleteUser($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id); // Buscar incluso si está eliminado lógicamente
    
            // Prevenir eliminar al admin (ID 1)
            if ($user->id === 1) {
                return response()->json([
                    'message' => 'El usuario admin no puede ser eliminado'
                ], 403); // 403: Prohibido
            }
    
            $user->tokens()->delete();   // Revocar tokens de acceso (Sanctum)
            $user->forceDelete();        // Eliminar definitivamente de la BD
    
            return response()->json([
                'message' => 'Usuario eliminado correctamente'
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Error al eliminar el usuario con ID $id. " . $e->getMessage()
            ], 500);
        }
    }
}