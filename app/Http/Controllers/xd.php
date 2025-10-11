<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function index()
    {
        if (Auth::check()) {
            $items = CartItem::with('producto')
                        ->where('user_id', Auth::id())
                        ->get();
        } else {
            $cart = session()->get('cart', []);
            $items = collect($cart); // convierte array en colección para usar en la vista
        }

        return view('shopcart', ['cartItems' => $items]);
    }



    public function addToCart(Request $request, $productoId)
    {
        $producto = Producto::findOrFail($productoId);
        $cantidad = max(1, intval($request->input('cantidad', 1)));

        if (Auth::check()) {
            // Usuario autenticado: guardar en base de datos
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
            // Usuario NO autenticado: guardar en sesión
            $cart = session()->get('cart', []);

            if (isset($cart[$productoId])) {
                $cart[$productoId]['cantidad'] += $cantidad;
            } else {
                $cart[$productoId] = [
                    'producto_id' => $productoId,
                    'nombre' => $producto->nombre,
                    'precio' => $producto->precio,
                    'cantidad' => $cantidad,
                ];
            }

            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Producto agregado al carrito');
    }

    public function mergeSessionCart()
    {
        if (!Auth::check()) return;

        $sessionCart = session()->get('cart', []);
        
        foreach ($sessionCart as $productoId => $item) {
            $cartItem = CartItem::where('user_id', Auth::id())
                                ->where('producto_id', $productoId)
                                ->first();

            if ($cartItem) {
                $cartItem->cantidad += $item['cantidad'];
                $cartItem->save();
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'producto_id' => $productoId,
                    'cantidad' => $item['cantidad']
                ]);
            }
        }

        session()->forget('cart');
    }


    public function update(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);
        
        if ($request->action == 'increase') {
            $cartItem->cantidad += 1;
        } else if ($request->action == 'decrease' && $cartItem->cantidad > 1) {
            $cartItem->cantidad -= 1;
        }
        
        $cartItem->save();
        
        return redirect()->back()->with('success', 'Cantidad actualizada');
    }

    public function removeFromCart($id)
    {
        $cartItem = CartItem::findOrFail($id);
        
        if ($cartItem->user_id === Auth::id()) {
            $cartItem->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Producto eliminado del carrito');
    }
}
