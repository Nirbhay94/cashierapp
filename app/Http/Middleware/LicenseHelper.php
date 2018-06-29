<?php
namespace App\Http\Middleware;

use App\Http\Middleware\Traits\Verification;
use Closure;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class LicenseHelper extends LicenseServer
{
    use Verification;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param   string|null
     * @return mixed
     */
    public function handle($request, Closure $next, $type = null)
    {
        if(!$this->inExceptArray($request)) {
            if (Cache::has('verification_code')) {
                $verification_code = Cache::get('verification_code');
                
                $details = $this->details($verification_code);

                if (!is_object($details)) {
                    if (is_array($details) && isset($details['error'])) {
                        return redirect()->route('LaravelVerify::overview')
                            ->with('message', ['content' => $details['message']]);
                    } else {
                        return redirect()->route('LaravelVerify::overview');
                    }
                }

                view()->share(['purchase_details' => $details]);
            } else {
                if(!File::exists(storage_path('installed'))){
                    return redirect()->route('LaravelInstaller::welcome');
                }else{
                    return redirect()->route('LaravelVerify::overview');
                }
            }
        }

        if($type !== null && !$this->checkLicense($details, $type)){
            $message = __('Your license does not support this feature!');

            return redirect()->route('home')->with('error', $message);
        }
        
        return $next($request);
    }

    public function checkLicense(PurchaseDetails $details, $type)
    {
        return ($details->isRegularLicense() && $type == 'regular') 
            || ($details->isExtendedLicense() && $type == 'extended');
    }
    
    public function details($verification_code)
    {
        if(!Cache::has('purchase_details')){
            try{
                $response = $this->client->get($this->server, $this->options($verification_code, true));
                
                $status_code = $response->getStatusCode();

                if($status_code == 200){
                    $license_details = (string) $response->getBody();
                    
                    Cache::put('purchase_details', $license_details, now()->addDay());

                    return new PurchaseDetails($license_details);
                }else{
                    return $this->errorMessage($response);
                }
            } catch (ClientException $e) {
                return $this->errorMessage($e->getResponse());
            }
        }else{
            $license_details = Cache::get('purchase_details');
            
            return new PurchaseDetails($license_details);
        }
    }

    public function register($verification_code)
    {
        if(!Cache::has('purchase_details')){
            try{
                $response = $this->client->post($this->server, $this->options($verification_code));
                
                $status_code = $response->getStatusCode();

                if($status_code == 200){
                    $license_details = (string) $response->getBody();
                
                    Cache::put('purchase_details', $license_details, now()->addDay());

                    return new PurchaseDetails($license_details);
                }else{
                    return $this->errorMessage($response);
                }
            } catch (ClientException $e) {
                return $this->errorMessage($e->getResponse());
            }
        }else{
            $license_details = Cache::get('purchase_details');
            
            return new PurchaseDetails($license_details);
        }
    }
}
