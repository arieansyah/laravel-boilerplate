<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

/**
 * Class Handler.
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        GeneralException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     *
     * @throws Exception
     * @return mixed|void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        // if ($exception instanceof UnauthorizedException) {
        //     return redirect()
        //         ->route(home_route())
        //         ->withFlashDanger(__('auth.general_error'));
        // }

        // return parent::render($request, $exception);
        $debug = config('app.debug');
        $message = '';
        $status_code = 500;
        if ($exception instanceof ModelNotFoundException) {
            $message = 'Resource is not found';
            $status_code = 404;
        } elseif ($exception instanceof NotFoundHttpException) {
            $message = 'Endpoint is not found';
            $status_code = 404;
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $message = 'Method is not allowed';
            $status_code = 405;
        } else if ($exception instanceof ValidationException) {
            $validationErrors = $exception->validator->errors()->getMessages();
            $validationErrors = array_map(function ($error) {
                return array_map(function ($message) {
                    return $message;
                }, $error);
            }, $validationErrors);
            $message = $validationErrors;
            $status_code = 405;
        } else if ($exception instanceof QueryException) {
            if ($debug) {
                $message = $exception->getMessage();
            } else {
                $message = 'Query failed to execute';
            }
            $status_code = 500;
        }
        $rendered = parent::render($request, $exception);
        $status_code = $rendered->getStatusCode();
        if (empty($message)) {
            $message = $exception->getMessage();
        }
        $errors = [];
        if ($debug) {
            $errors['exception'] = get_class($exception);
            $errors['trace'] = explode("\n", $exception->getTraceAsString());
        }
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => null,
            'errors' => $errors,
        ], $status_code);

    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param AuthenticationException  $exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
        ? response()->json(['message' => 'Unauthenticated.'], 401)
        : redirect()->guest(route('frontend.auth.login'));
    }
}
