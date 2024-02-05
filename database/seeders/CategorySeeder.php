<?php


namespace Database\Seeders;


use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create(['name' => 'Analgesics']);
        Category::create(['name' => 'Antibiotics']);
        Category::create(['name' => 'Antidepressants']);
        Category::create(['name' => 'Antihypertensives']);
        Category::create(['name' => 'Antidiabetics']);
        Category::create(['name' => 'Antihistamines']);
        Category::create(['name' => 'Antacids']);
        Category::create(['name' => 'Bronchodilators']);
        Category::create(['name' => 'Anticoagulants']);
        Category::create(['name' => 'Antiemetics']);
    }
}
