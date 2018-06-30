<?php

namespace RachidLaasri\LaravelInstaller\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class FreshInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Performs necessary cleanup, for a fresh installation.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->cleanFiles();
        $this->cleanVariables();
        $this->cleanDirectory();
    }

    public function cleanFiles()
    {
        File::delete(storage_path('installed'));
        File::delete(base_path('.env'));
    }

    public function cleanVariables()
    {
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        session()->flush();
    }

    public function cleanDirectory()
    {
        File::deleteDirectory(public_path('images/uploads'));
        File::deleteDirectory(public_path('files'));
        File::deleteDirectory(storage_path('users'));
    }
}
