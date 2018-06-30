<?php

namespace RachidLaasri\LaravelInstaller\Helpers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;

class InstalledFileManager
{
    /**
     * Create installed file.
     *
     * @return int
     */
    public function create()
    {
        $log_file = storage_path('installed');
        $date_stamp = date("Y/m/d h:i:sa");
        $version_path = base_path('version');

        try{
            $version = File::get($version_path);
            if(strlen($version) > 10){
                $version = 'alpha';
            }
        }catch(FileNotFoundException $e){
            $version = 'beta';
        }

        if (!file_exists($log_file)) {
            $message = config('installer.name').': Installed'."\n";
            $message .= 'Date: '.$date_stamp."\n";
            $message .= 'Version: '.$version."\n\n";

            file_put_contents($log_file, $message);
        } else {
            $message = config('installer.name').': Updated'."\n";
            $message .= 'Date: '.$date_stamp . "\n";
            $message .= 'Version: '.$version."\n\n";

            file_put_contents($log_file, $message.PHP_EOL , FILE_APPEND | LOCK_EX);
        }

        return true;
    }
}