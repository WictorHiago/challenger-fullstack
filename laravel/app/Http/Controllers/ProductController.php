<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function findAll()
    {
        try {
            $products = Product::all();

            return response()->json([
                'products' => $products,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erro ao buscar produtos',
            ], 500);
        }
    }

    public function findById($id)
    {
        try {
            $product = Product::find($id);

            return response()->json([
                'product' => $product,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erro ao buscar produto',
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:50|unique:products,name',
                'description' => 'required|string|max:200',
                'price' => 'required|numeric',
                'image_url' => 'required|string',
                'category_id' => 'required|exists:categories,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors(),
                ], 422);
            }

            $product = Product::create($request->all());

            return response()->json([
                'product' => $product,
            ], 201);
        }
         catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erro ao criar produto',
            ], 500);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'error' => 'Produto não encontrado',
                ], 404);
            }

            $product->update($request->only([
                'name',
                'price',
                'description',
                'image_url',
                'category_id',
            ]));

            // validar tamanho do name e description


            return response()->json([
                'product' => $product,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erro ao atualizar produto',
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'error' => 'Produto não encontrado',
                ], 404);
            }

            $product->delete();

            return response()->json([
                'message' => 'Produto deletado com sucesso',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erro ao deletar produto',
            ], 500);
        }
    }
}
