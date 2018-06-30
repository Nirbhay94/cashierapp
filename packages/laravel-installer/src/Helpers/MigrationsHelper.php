<?php

namespace RachidLaasri\LaravelInstaller\Helpers;

use Exception;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

trait MigrationsHelper {

    /**
     * Get the migrations in /database/migrations
     *
     */
    public function getMigrations()
    {
        $migrations = glob(database_path('migrations'.DIRECTORY_SEPARATOR.'*.php'));

        return str_replace('.php', '', $migrations);
    }

    /**
     * Get the migrations that have already been ran.
     *
     */
    public function getExecutedMigrations()
    {
        // Migrations table should exist, if not, user will receive an error.
        return DB::table('migrations')->get()->pluck('migration');
    }

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
            return redirect()->route('LaravelUpdater::overview')
                ->with('error', $e->getMessage());
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
            return redirect()->route('LaravelUpdater::overview')
                ->with('error', $e->getMessage());
        }

        Session::put('update_complete', true);
        return redirect()->route('LaravelUpdater::final');
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
