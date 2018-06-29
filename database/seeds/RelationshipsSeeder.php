<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RelationshipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if($role = Role::where('name', 'admin')->first()){
            $permissions = Permission::whereNotIn('name', [
                'subscribe to services'
            ])->get();

            $role->syncPermissions($permissions);
        }

        if($role = Role::where('name', 'user')->first()){
            $permissions = Permission::whereIn('name', [
                'subscribe to services'
            ])->get();

            $role->syncPermissions($permissions);
        }
    }
}
