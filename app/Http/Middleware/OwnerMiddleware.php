<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Models\User;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()->privilege == 1){
            return $next($request);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
