<?php
namespace App\Http\Middleware;

use Closure;
use Exception;
use Auth;

/**
*
* @version 0.1.4.5
* @license Copyright Empresa 2018. Todos los derechos reservados.
* @author Junior Milano - Desarrollador Web
* @overview Clase que sirve de middleware de cada request por parte de los conductores, verifica que haya sesión abierta
*
**/

class SessionJWTMiddleware
{

   /**
   * Handle que verifica que el usuario tenga sesión abierta
   * @author Junior Milano <renshocontact@gmail.com>
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   * @memberof SessionJWTMiddleware
   */

    public function handle($request, Closure $next, $guard = null)
    {
        try {
            if (!Auth::guard('jwt')->check()) {
                return ['status'=>'error','data'=>['message'=>\Lang::get('messages.not_token')]];
            }
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return ['status'=>'error','data'=>['message'=>\Lang::get('messages.token_invalid')]];
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return ['status'=>'error','data'=>['message'=>\Lang::get('messages.token_expired')]];
            } else {
                return ['status'=>'error','data'=>['message'=>\Lang::get('messages.something_wrong')]];
            }
        }
        return $next($request);
    }
}
