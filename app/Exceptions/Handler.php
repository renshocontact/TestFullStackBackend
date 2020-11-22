<?php
namespace App\Exceptions;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
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
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $errorFounded=false;
        $lang = explode('-', \Request::server('HTTP_ACCEPT_LANGUAGE'))[0];

        if (trim($lang)!=='') {
            \App::setLocale($lang);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {
            $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('messages.not_token_found'))]];
        } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            if ($request->ajax()) {
                $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('messages.not_direction_ajax'))]];
            } else {
                $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('messages.not_direction'))]];
            }
        } elseif ($exception instanceof TokenInvalidException) {
            $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('messages.token_invalid'))]];
        } elseif ($exception instanceof TokenExpiredException) {
            $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('messages.token_expired'))]];
        } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            $errorFounded =  ['status'=>'error','data'=>['message'=>htmlentities(\Lang::get('messages.not_method'))]];
        }

        if ($errorFounded) {
            return response()->json($errorFounded, 500, ['content-type'=>'application/json; charset=utf-8']);
        }

        return parent::render($request, $exception);
    }
}
