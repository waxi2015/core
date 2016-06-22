<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        if (env('APP_DEBUG') === false && !$this->isHttpException($e)) {
            $email = new \Email;
            $email->send([
                'subject' => 'Error at ' . env('APP_NAME'),
                'message' => $e->getCode() . ': ' . $e->getMessage() . '<br/><br/>' .  $e->getFile() . ':' . $e->getLine() . '<br/><br/><pre>' . $e->getTraceAsString() . '</pre>',
                'to' => [
                    env('ADMINISTRATOR_EMAIL') => env('ADMINISTRATOR_NAME'),
                ]
            ]);
        }

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if (env('APP_DEBUG') === false && !$this->isHttpException($e)) {
            return response()->view('errors.500');
        }

        return parent::render($request, $e);
    }
}
