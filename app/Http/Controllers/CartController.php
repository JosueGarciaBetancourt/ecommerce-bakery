<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\PedidoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $cartItems = Auth::user()->cartItems()->with('producto')->get();
        } else {
            $sessionCart = session()->get('cart', []);
            $cartItems = collect();

            foreach ($sessionCart as $productoId => $item) {
                $producto = Producto::find($productoId);
                if ($producto) {
                    $cartItems->push((object)[
                        'id' => $productoId,
                        'producto' => $producto,
                        'cantidad' => $item['cantidad'] ?? 1 // Por si no existe la clave
                    ]);
                }
            }

        }

        return view('shopcart', compact('cartItems'));
    }

    public function addToCart(Request $request, $productoId)
    {
        $cantidad = max(1, intval($request->input('cantidad', 1)));
        $producto = Producto::findOrFail($productoId);

        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())
                                ->where('producto_id', $productoId)
                                ->first();

            if ($cartItem) {
                $cartItem->cantidad += $cantidad;
                $cartItem->save();
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'producto_id' => $productoId,
                    'cantidad' => $cantidad
                ]);
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$productoId])) {
                $cart[$productoId]['cantidad'] += $cantidad;
            } else {
                $cart[$productoId] = [
                    'name' => $producto->name,
                    'description' => $producto->description,
                    'price' => $producto->price,
                    'file_uri' => $producto->file_uri,
                    'cantidad' => $cantidad
                ];
            }
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Producto agregado al carrito');
    }

    public function update(Request $request, $id)
    {
        if (Auth::check()) {
            $cartItem = CartItem::findOrFail($id);

            if ($cartItem->user_id !== Auth::id()) {
                abort(403); // Prohibido
            }

            if ($request->action == 'increase') {
                $cartItem->cantidad += 1;
            } elseif ($request->action == 'decrease' && $cartItem->cantidad > 1) {
                $cartItem->cantidad -= 1;
            }

            $cartItem->save();

        } else {
            // Usuario no autenticado → trabajar con sesión
            $cart = session()->get('cart', []);

            if (!isset($cart[$id])) {
                return redirect()->back()->with('error', 'Producto no encontrado en el carrito');
            }

            if ($request->action == 'increase') {
                $cart[$id]['cantidad'] += 1;
            } elseif ($request->action == 'decrease' && $cart[$id]['cantidad'] > 1) {
                $cart[$id]['cantidad'] -= 1;
            }

            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Cantidad actualizada');
    }


    public function removeFromCart($id)
    {
        if (Auth::check()) {
            $cartItem = CartItem::findOrFail($id);

            if ($cartItem->user_id !== Auth::id()) {
                abort(403); // Prohibido
            }

            $cartItem->delete();

        } else {
            $cart = session()->get('cart', []);

            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Producto eliminado del carrito');
    }

    public function checkout()
    {
        if (Auth::check()) {
            $cartItems = Auth::user()->cartItems()->with('producto')->get();

            if ($cartItems->isEmpty()) {
                return redirect()->back()->with('error', 'El carrito está vacío');
            }

            // Calcular total
            $total = $cartItems->sum(fn($item) => $item->producto->price * $item->cantidad);

            // Crear pedido
            $pedido = Pedido::create([
                'user_id' => Auth::id(),
                'total' => $total
            ]);

            // Crear pedido_items
            foreach ($cartItems as $item) {
                PedidoItem::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item->producto->id,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->producto->price
                ]);
            }

            // Vaciar el carrito
            CartItem::where('user_id', Auth::id())->delete();

        } else {
            $cart = session()->get('cart', []);
            if (empty($cart)) {
                return redirect()->back()->with('error', 'El carrito está vacío');
            }

            $total = collect($cart)->sum(fn($item) => $item['price'] * $item['cantidad']);

            $pedido = Pedido::create([
                'user_id' => null, // Usuario invitado
                'total' => $total
            ]);

            foreach ($cart as $productoId => $item) {
                PedidoItem::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $productoId,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['price']
                ]);
            }

            session()->forget('cart');
        }

        return redirect()->route('pedidos.show', $pedido->id)->with('success', '¡Pedido realizado con éxito!');
    }

}
