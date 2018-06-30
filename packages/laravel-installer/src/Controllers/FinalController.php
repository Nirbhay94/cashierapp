<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use RachidLaasri\LaravelInstaller\Helpers\DatabaseManager;
use RachidLaasri\LaravelInstaller\Helpers\FinalInstallManager;
use RachidLaasri\LaravelInstaller\Helpers\InstalledFileManager;

class FinalController extends Controller
{
    protected $final;
    protected $db;

    public function __construct(FinalInstallManager $final, DatabaseManager $db)
    {
        $this->final = $final;
        $this->db = $db;
    }

    /**
     * Update installed file and display finished view.
     *
     * @param InstalledFileManager $file_manager
     * @return \Illuminate\View\View
     */
    public function finish(InstalledFileManager $file)
    {
        $result = $this->db->migrateAndSeed();

        if($result['status'] != 'error') {
            Cache::forever('verification_code', session()->get('verification_code'));
            
            if($this->final->run()){
                if($file->create()) {
                    return view('vendor.installer.finished');
                }
            }

            $message = __('Oops! Something unexpected occurred!');

            return redirect()->route('LaravelInstaller::environment')
                ->with('error', $message);
        }else{
            return redirect()->route('LaravelInstaller::environment')
                ->with('error', $result['message']);
        }
    }
}
