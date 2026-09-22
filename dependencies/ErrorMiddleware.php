<?php

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpException;
use Slim\Middleware\ErrorMiddleware;
use Slim\App;

return [
    ErrorMiddleware::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);

        // NB: these keys are not always present in every .env (they're
        // missing from .env.example), so fall back instead of letting
        // (bool)$_ENV['...'] raise an "Undefined array key" warning that
        // gets printed straight into the response body (since
        // display_errors is on in bootstrap.php), corrupting the JSON
        // of every single response, success or error.
        $displayErrorDetails = filter_var($_ENV['display_error_details'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $logErrors = filter_var($_ENV['log_errors'] ?? true, FILTER_VALIDATE_BOOLEAN);
        $logErrorDetails = filter_var($_ENV['log_error_details'] ?? true, FILTER_VALIDATE_BOOLEAN);

        $middleware = new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            $displayErrorDetails,
            $logErrors,
            $logErrorDetails
        );

        // Controllers only ever `catch (\Error $e)` and hand the result to
        // the app's own 'error' container helper, which always responds
        // with {"exception": {"message": "..."}}. Anything that ISN'T an
        // \Error (a real \Exception - e.g. an Eloquent QueryException, a
        // JSON decode failure, a 3rd-party HTTP client error - or a Slim
        // routing error like 404/405) skips every controller's try/catch
        // entirely and lands here instead, where Slim's own default
        // handler would normally return a *different* JSON shape
        // ({"message": "..."} in production, or {"message": ..., "exception":
        // [...]} - an ARRAY - with display_error_details on).
        //
        // That shape mismatch is exactly what the frontend's exception
        // service was tripping over. Normalize everything to the same
        // shape here so the frontend only ever has to handle one format,
        // regardless of what actually failed or where.
        $middleware->setDefaultErrorHandler(
            function (
                Request $request,
                Throwable $exception,
                bool $displayErrorDetails
            ) use ($app, $logErrors): ResponseInterface {
                $status = $exception instanceof HttpException
                    ? $exception->getCode()
                    : 500;

                if ($status < 400 || $status > 599)
                {
                    $status = 500;
                }

                $message = $exception->getMessage();

                if (!$displayErrorDetails && !($exception instanceof HttpException))
                {
                    // Don't leak internals (SQL text, file paths, stack
                    // traces) to the client outside of local debugging.
                    $message = "Terjadi kesalahan pada server. Silakan coba lagi.";
                }

                if ($logErrors)
                {
                    error_log((string) $exception);
                }

                $payload = [
                    'exception' => [
                        'message' => $message
                    ]
                ];

                $response = $app->getResponseFactory()->createResponse($status);
                $response->getBody()->write(json_encode($payload));

                return $response->withHeader('Content-Type', 'application/json');
            }
        );

        return $middleware;
    },

];
