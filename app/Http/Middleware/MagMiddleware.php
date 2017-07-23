<?php

namespace App\Http\Middleware;

use Closure;

class MagMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
		if ($request->user() && $request->user()['admin']) {
			if ($request->isMethod('get') && !$request->is('admin')) return redirect('/admin');
			//echo("22");
			return $next($request);
		}
		else {
			if ($request->is('admin')) return redirect('/');
			return $next($request);
		} 


    }
}
