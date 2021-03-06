<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        return abort(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param $request
     * @param Closure $next
     * @param ...$guards
     * @return mixed
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if ($jwt = $request->cookie('jwt')){
            $request->headers->set('Authorization','Bearer ' . $jwt);
        }

        $this->authenticate($request,$guards);

        return $next($request);
    }
}
