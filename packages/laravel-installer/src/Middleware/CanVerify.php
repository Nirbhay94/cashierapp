<?php
/**
 * Created by PhpStorm.
 * User: HolluwaTosin
 * Date: 6/9/2018
 * Time: 10:21 AM
 */

namespace RachidLaasri\LaravelInstaller\Middleware;

use App\Http\Middleware\LicenseHelper;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class CanVerify extends LicenseHelper
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Cache::has('verification_code')) {
            $verification_code = Cache::get('verification_code');

            /*$purchase_details = $this->details($verification_code);

            if (is_object($purchase_details)) {
                return abort(404);
            }*/

        } else {
            /*if(!File::exists(storage_path('installed'))){
                return redirect()->route('LaravelInstaller::welcome');
            }*/
        }

        return $next($request);
    }
}