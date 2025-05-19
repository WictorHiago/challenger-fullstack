<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function findAll()
    {
        try {
            $categories = Category::all();

            return response()->json([
                'categories' => $categories,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erro ao buscar categorias',
            ], 500);
        }
    }

    public function findById($id)
    {
        try {
            $category = Category::findOrFail($id);

            return response()->json([
                'category' => $category,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erro ao buscar categoria',
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $category = Category::create($request->only([
                'name',
            ]));

            return response()->json([
                'category' => $category,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erro ao criar categoria',
            ], 500);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $category = Category::where('id', $id)->first();
            if (!$category) {
                return response()->json([
                    'error' => 'Categoria não encontrada',
                ], 404);
            }

            $category->update($request->only([
                'name',
            ]));

            if (!$category) {
                return response()->json([
                    'error' => 'Erro ao atualizar categoria',
                ], 500);
            }


            return response()->json([
                'category' => $category,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erro ao atualizar categoria',
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $category = Category::where('id', $id)->first();
            if (!$category) {
                return response()->json([
                    'error' => 'Categoria não encontrada',
                ], 404);
            }

            $category->delete();

            return response()->json([
                'message' => 'Categoria deletada com sucesso',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erro ao deletar categoria',
            ], 500);
        }
    }
}
