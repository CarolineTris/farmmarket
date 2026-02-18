<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'verified_farmer' => \App\Http\Middleware\VerifiedFarmerMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, Request $request) {
            if (config('app.debug')) {
                return null;
            }

            $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

            if ($request->expectsJson()) {
                $defaultMessage = $status >= 500
                    ? 'Something went wrong. Please try again later.'
                    : 'Request could not be completed.';

                return response()->json([
                    'message' => $defaultMessage,
                ], $status);
            }

            $view = view()->exists("errors.{$status}") ? "errors.{$status}" : 'errors.generic';

            return response()->view($view, ['status' => $status], $status);
        });
    })->create();
