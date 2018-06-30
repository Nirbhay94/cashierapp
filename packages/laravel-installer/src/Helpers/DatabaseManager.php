<?php

namespace RachidLaasri\LaravelInstaller\Helpers;

use Exception;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Console\Output\BufferedOutput;

class DatabaseManager
{
    /**
     * Migrate and seed the database.
     *
     */
    public function migrateAndSeed()
    {
        return $this->migrate();
    }

    /**
     * Run the migration and call the seeder.
     *
     */
    private function migrate()
    {
        $this->sqlite();

        try{
            Artisan::call('migrate', ["--force" => true]);
        }catch(Exception $e){
            return ['status' => 'error', 'message' => $e->getMessage()];
        }

        return $this->seed();
    }

    /**
     * Seed the database.
     *
     */
    private function seed()
    {
        try{
            Artisan::call('db:seed', ["--force" => true]);
        } catch(Exception $e){
            return ['status' => 'error', 'message' => $e->getMessage()];
        }

        return ['status' => 'success'];
    }

    /**
     * Check database type. If SQLite, then create the database file.
     *
     */
    private function sqlite()
    {
        if(DB::connection() instanceof SQLiteConnection) {
            $database = DB::connection()->getDatabaseName();
            if(!file_exists($database)) {
                touch($database);
                DB::reconnect(Config::get('database.default'));
            }
        }
    }
}
