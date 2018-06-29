<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class GlobalVariables
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
        try{
            if($settings = Setting::first()){
                View::share(compact('settings'));
            }else{
                throw new Exception();
            }
        }catch(Exception $e){
            File::delete(storage_path('installed'));

            return redirect()->route('LaravelInstaller::welcome');
        }

        return $next($request);
    }
}
