<?php

namespace App\Http\Middleware;

use App\Traits\ThrottlesEmails;
use Closure;

class IsVerified
{
    use ThrottlesEmails;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(! is_null($request->user()) && ! $request->user()->verified) {
            $message = __('You need to verify your email address.');

            if($request->ajax()){
                return response()->json($message, 403);
            }else{
                return redirect()->back()->with('error', $message);
            }
        }

        return $next($request);
    }
}
