<?php

namespace App\Http\Middleware;

use App\Developer;
use Closure;

class MustBeDeveloper {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $user = $request->user();

        if (!$user) {
            return back();
        }

        $developer = new Developer();

        $exists = $developer->exists($user->id);

        if ($exists == false) {
            return back();
        }

        return $next($request);
    }

}
