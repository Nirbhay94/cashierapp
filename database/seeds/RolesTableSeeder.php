<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Role::where('name', 'admin')->first()) {
            Role::create(['name' => 'admin']);
        }

        if (!Role::where('name', 'user')->first()) {
            Role::create(['name' => 'user']);
        }
    }
}
