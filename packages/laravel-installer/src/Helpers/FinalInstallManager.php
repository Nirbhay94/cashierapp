<?php

namespace RachidLaasri\LaravelInstaller\Helpers;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Console\Output\BufferedOutput;

class FinalInstallManager
{
    /**
     * Run final commands.
     *
     */
    public function run()
    {
        return $this->generateKey();
    }

    /**
     * Generate New Application Key.
     *
     */
    private static function generateKey()
    {
        try{
            Artisan::call('key:generate', ["--force"=> true]);

            return true;
        }catch(Exception $e){
            return false;
        }
    }

}
