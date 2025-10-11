<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OpinionController;
use App\Http\Controllers\PedidoController;


// Rutas de inicio
Route::get('/', [HomeController::class, 'inicio']);
Route::get('home', HomeController::class)->name('home');

// Rutas de Breeze
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas que necesitan sesión (protegidas)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::controller(ProductController::class)->group(function(){
        Route::post('productos', 'store')->name('productos.store');
        Route::delete('/productos/{producto}', 'destroy')->name('productos.destroy');
        Route::get('productos/{producto}/edit', 'edit')->name('productos.edit');
        Route::put('productos/{producto}', 'update')->name('productos.update');
    });



    Route::controller(OpinionController::class)->group(function(){
        Route::post('opiniones', 'store')->name('opiniones.store');
    });

    // Paga del carrito
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
    // Vista de todos los pedidos
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    // Vista con el pedido
    Route::get('/pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');

});

// ❗ Rutas públicas del carrito (funcionan sin loguearse)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{productoId}', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');


// Rutas de productos
Route::controller(ProductController::class)->group(function(){
    Route::get('productos/{producto}', 'show')->name('productos.show');
    Route::get('productos', 'index')->name('productos.index');
});

// Incluir autenticación de Breeze
require __DIR__.'/auth.php';
