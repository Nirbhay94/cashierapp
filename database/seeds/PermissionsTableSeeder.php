<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!Permission::where('name', 'read users')->first()){
            Permission::create([
                'name' => 'read users',
                'description' => 'This permission is required to view table list of users'
            ]);
        }

        if(!Permission::where('name', 'alter users')->first()){
            Permission::create([
                'name' => 'alter users',
                'description' => 'This permission is required to add, edit or delete users. It works together with read users permission'
            ]);
        }

        if(!Permission::where('name', 'manage plans')->first()){
            Permission::create([
                'name' => 'manage plans',
                'description' => 'This permission is required to add, edit or delete plans'
            ]);
        }

        if(!Permission::where('name', 'manage permissions')->first()){
            Permission::create([
                'name' => 'manage permissions',
                'description' => 'This permission is required to add, edit or delete user roles and permissions'
            ]);
        }

        if(!Permission::where('name', 'subscribe to services')->first()){
            Permission::create([
                'name' => 'subscribe to services',
                'description' => 'This permission requires subscription for the the use of services'
            ]);
        }

        if(!Permission::where('name', 'view financial data')->first()){
            Permission::create([
                'name' => 'view financial data',
                'description' => 'This permission is required to view financial data like invoices e.t.c'
            ]);
        }

        if(!Permission::where('name', 'configure services')->first()){
            Permission::create([
                'name' => 'configure services',
                'description' => 'This permission is required to manage services used by the application'
            ]);
        }

        if(!Permission::where('name', 'configure settings')->first()){
            Permission::create([
                'name' => 'configure settings',
                'description' => 'This permission is required to manage site settings'
            ]);
        }
    }
}
