<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use RachidLaasri\LaravelInstaller\Helpers\InstalledFileManager;
use RachidLaasri\LaravelInstaller\Helpers\DatabaseManager;

class UpdateController extends Controller
{
    use \RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;

    /**
     * Display the updater welcome page.
     *
     * @return \Illuminate\View\View
     */
    public function welcome()
    {
        return view('vendor.installer.update.welcome');
    }

    /**
     * Display the updater overview page.
     *
     * @return \Illuminate\View\View
     */
    public function overview()
    {
        $dbMigrations = $this->getExecutedMigrations();
        $migrations = $this->getMigrations();
        $pendingUpdates = count($migrations) - count($dbMigrations);

        return view('vendor.installer.update.overview', [
            'numberOfUpdatesPending' => $pendingUpdates
        ]);
    }

    /**
     * Migrate and seed the database.
     *
     * @return \Illuminate\View\View
     */
    public function database()
    {
        return $this->migrateAndSeed();
    }

    /**
     * Update installed file and display finished view.
     *
     * @param InstalledFileManager $fileManager
     * @return \Illuminate\View\View
     */
    public function finish(InstalledFileManager $file)
    {
        if(Session::has('update_complete') && Session::get('update_complete')) {
            $file->create();

            return view('vendor.installer.update.finished');
        }else{
            return redirect()->route('LaravelUpdater::overview');
        }
    }
}
