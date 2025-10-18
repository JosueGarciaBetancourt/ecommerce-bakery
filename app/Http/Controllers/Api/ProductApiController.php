<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class ProductApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::query()
            ->withAvg('opinions as rating', 'estrellas'); // 🔹 Promedio de estrellas

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->has('sort')) {
            $sort = $request->sort;
            if ($sort == 'price-asc') $query->orderBy('price', 'asc');
            if ($sort == 'price-desc') $query->orderBy('price', 'desc');
        }

        $productos = $query->get();

        return response()->json([
            'success' => true,
            'data' => $productos,
        ]);
    }

    public function show($id)
    {
        $producto = Producto::withAvg('opinions as rating', 'estrellas')->find($id);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $producto,
        ]);
    }
}
