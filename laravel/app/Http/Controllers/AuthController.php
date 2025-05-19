<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            if (Auth::attempt($request->all('email', 'password'))) {
                $user = User::where('email', $request->email)->first();

                if (!$user) {
                    return response()->json([
                        'error' => 'Usuário não encontrado',
                    ], 404);
                }

                if (!Hash::check($request->password, $user->password)) {
                    return response()->json([
                        'error' => 'Credenciais inválidas',
                    ], 401);
                }

                $token = $request->user()->createToken('access_token')->plainTextToken;

                return response()->json([
                    'user' => $user,
                    'token' => $token,
                ]);
            }

            return response()->json([
                'error' => 'Unauthorized',
            ], 401);

        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'user' => $user,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logout realizado com sucesso',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
