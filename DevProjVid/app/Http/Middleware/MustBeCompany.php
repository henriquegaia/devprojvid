<?php

namespace App\Http\Middleware;

use Closure;
use App\Company;

class MustBeCompany {

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

        $company = new Company();

        $exists = $company->exists($user->id);

        if ($exists == false) {
            return back();
        }

        return $next($request);
    }

}
