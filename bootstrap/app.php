<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // ✅ Esto agrega soporte real para rutas API
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Si la solicitud es para una API, responde siempre con JSON
        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Usuario no autenticado.'
                ], 401);
            }

            // Si no es una solicitud JSON, se maneja como HTML (o cualquier otro formato por defecto)
            return response('No autenticado', 401);
        });

        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'No estás autorizado para realizar esta acción.'
                ], 403);
            }

            // Si no es una solicitud JSON, se maneja como HTML (o cualquier otro formato por defecto)
            return response('No autorizado', 403);
        });

        // Para errores 404
        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Recurso no encontrado.'
                ], 404);
            }

            return response('Recurso no encontrado', 404);
        });

        // Para errores 404
        $exceptions->renderable(function (\Symfony\Component\Routing\Exception\RouteNotFoundException $e, $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Recurso web no encontrado.'
                ], 404);
            }

            return response('Recurso web no encontrado', 404);
        });

        // Para errores 500
        $exceptions->renderable(function (\Exception $e, $request) {
            \Log::info($e);
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Hubo un error en el servidor, por favor inténtalo más tarde.'
                ], 500);
            }

            return response('Error en el servidor', 500);
        });
    })->create();
