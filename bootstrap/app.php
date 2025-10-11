<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/*
$request->expectsJson()

headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
}
*/

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*') && $request->expectsJson()) {
                return response()->json([
                    'message' => 'Método HTTP no permitido. Asegúrate de usar el método correcto (POST para login).'
                ], 405);
            }
        });
        
        $exceptions->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*') && $request->expectsJson()) {
                return response()->json([
                    'message' => 'Token inválido o expirado. Por favor, inicia sesión nuevamente.'
                ], 401);
            }
        });

        $exceptions->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*') && $request->expectsJson()) {
                return response()->json([
                    'message' => 'La ruta solicitada no existe o no está disponible.'
                ], 404);
            }
        });
    })->create();