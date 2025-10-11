<?php

namespace App\Http\Controllers;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{

    public function index()
    {
        $pedidos = Pedido::with('items.producto')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pedidos.index', compact('pedidos'));
    }

    public function show(Pedido $pedido)
    {
        // Protege para que solo el dueño pueda ver el pedido
        if ($pedido->user_id && $pedido->user_id !== auth()->id()) {
            abort(403);
        }

        $pedido->load(['items.producto', 'user']);
        return view('pedidos.show', compact('pedido'));
    }

}
