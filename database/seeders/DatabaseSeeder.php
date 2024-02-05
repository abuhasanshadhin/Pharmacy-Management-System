<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Role;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(UserSeeder::class);

        if(env('APP_DEMO')){
            $this->call(SupplierSeeder::class);
            $this->call(UnitSeeder::class);
            $this->call(CategorySeeder::class);
            $this->call(ProductSeeder::class);
            $this->call(GatewaySeeder::class);
            $this->call(PrescriptionSeeder::class);
        }
    }
}