<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    public function findAll()
    {
        try {
            $users = User::all();

            return response()->json([
                'users' => $users,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function findById($id)
    {
        try {
            $user = User::find($id);

            return response()->json([
                'user' => $user,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'error' => 'Usuário não encontrado',
                ], 404);
            }

            $user->update($request->all());

            return response()->json([
                'user' => $user,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'error' => 'Usuário não encontrado',
                ], 404);
            }

            $user->delete();

            return response()->json([
                'message' => 'Usuário deletado com sucesso',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
