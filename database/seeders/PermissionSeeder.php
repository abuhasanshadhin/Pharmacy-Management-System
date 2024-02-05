<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Utils\Utilites;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Utilites::permissions() ?? [];
        foreach ($permissions as $group => $permission) {
            foreach ($permission as $action) {
                $action['module'] = $group;
                $action['guard_name'] = 'web';
                $alreadyHas = Permission::where('name', $action['name'])->first();
                if (!$alreadyHas) {
                    Permission::create($action);
                } else {
                    $alreadyHas->update(['name' => $action['name']]);
                }
            }
        }
    }
}
