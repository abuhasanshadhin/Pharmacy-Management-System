<?php


namespace Database\Seeders;


use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run()
    {
        // Create sample units used in medicine
        Unit::create(['name' => 'Tablet']);
        Unit::create(['name' => 'Capsule']);
        Unit::create(['name' => 'Milligram (mg)']);
        Unit::create(['name' => 'Gram (g)']);
        Unit::create(['name' => 'Milliliter (ml)']);
        Unit::create(['name' => 'Drop']);
    }
}
