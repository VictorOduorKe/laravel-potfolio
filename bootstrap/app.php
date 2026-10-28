<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontFlash([
            'current_password',
            'password',
            'password_confirmation',
            'token',
            'otp',
            'code',
            'secret',
            'api_key',
            'api_token',
        ]);

        $exceptions->shouldRenderJsonWhen(function ($request, $e) {
            return $request->expectsJson() || $request->is('api/*');
        });

        // Ensure database query errors never expose raw SQL, bindings, or credentials in production
        $exceptions->render(function (\Illuminate\Database\QueryException $e, $request) {
            $rawMsg = $e->getMessage();
            $sanitized = preg_replace('/(\[bindings:.*?\])/is', '[bindings redacted]', $rawMsg);
            $sanitized = preg_replace('/(password|token|otp|secret|api_key)=([^\s&]+)/i', '$1=***', $sanitized);

            \Illuminate\Support\Facades\Log::error('Database query error: ' . $sanitized, [
                'sql_state' => $e->errorInfo[0] ?? null,
                'error_code' => $e->errorInfo[1] ?? null,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);

            if (config('app.debug')) {
                return null;
            }

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'A database error occurred. Please try again later.',
                ], 500);
            }

            return response()->view('errors.500', [], 500);
        });

        // Ensure PDO/connection errors never expose database host/port/user in production
        $exceptions->render(function (\PDOException $e, $request) {
            \Illuminate\Support\Facades\Log::error('Database connection error code: ' . $e->getCode(), [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);

            if (config('app.debug')) {
                return null;
            }

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Database connection failed. Please try again later.',
                ], 500);
            }

            return response()->view('errors.500', [], 500);
        });
    })->create();

// Designated single source of truth for secrets and environment configuration
$secretsDir = $app->basePath('secrets');
$secretsFile = 'my-portfolio.env';
$secretsPath = $secretsDir . DIRECTORY_SEPARATOR . $secretsFile;

// Validate that the designated secrets file exists and is readable when configuration is not cached
if (! $app->configurationIsCached()) {
    if (! file_exists($secretsPath)) {
        throw new \RuntimeException(
            "Application configuration error: Designated environment file [secrets/{$secretsFile}] does not exist."
        );
    }

    if (! is_readable($secretsPath)) {
        throw new \RuntimeException(
            "Application configuration error: Designated environment file [secrets/{$secretsFile}] is not readable. Check permissions."
        );
    }
}

// Instruct Laravel to load environment variables from the designated secrets directory and file
$app->useEnvironmentPath($secretsDir);
$app->loadEnvironmentFrom($secretsFile);

return $app;

