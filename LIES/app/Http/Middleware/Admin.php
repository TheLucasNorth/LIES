<?php namespace App\Http\Middleware;

use Closure;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;

class Admin {

    public function handle($request, Closure $next)
    {

        if ( Auth::check() && Auth::user()->isAdmin() )
        {
            return $next($request);
        }

        return redirect('elections');

    }

}