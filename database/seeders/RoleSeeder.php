<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role =  Role::create([
            'name' => 'supper_admin',
            'display_name' => 'Supper Admin'
        ]);

        $permissions = Permission::select('id', 'name', 'label', 'module')
            ->get()
            ->pluck('name','id');

        $role->syncPermissions($permissions);
    }
}
