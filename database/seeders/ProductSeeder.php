<?php


namespace Database\Seeders;


use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{

    public function run()
    {
        $medicines = [
            ['name' => 'Lisinopril', 'generic_name' => 'Lisinopril'],
            ['name' => 'Simvastatin', 'generic_name' => 'Simvastatin'],
            ['name' => 'Atorvastatin', 'generic_name' => 'Atorvastatin'],
            ['name' => 'Levothyroxine', 'generic_name' => 'Levothyroxine'],
            ['name' => 'Metformin', 'generic_name' => 'Metformin'],
            ['name' => 'Amlodipine', 'generic_name' => 'Amlodipine'],
            ['name' => 'Omeprazole', 'generic_name' => 'Omeprazole'],
            ['name' => 'Metoprolol', 'generic_name' => 'Metoprolol'],
            ['name' => 'Losartan', 'generic_name' => 'Losartan'],
            ['name' => 'Albuterol', 'generic_name' => 'Albuterol'],
            ['name' => 'Gabapentin', 'generic_name' => 'Gabapentin'],
            ['name' => 'Hydrochlorothiazide', 'generic_name' => 'Hydrochlorothiazide'],
            ['name' => 'Acetaminophen', 'generic_name' => 'Acetaminophen'],
            ['name' => 'Sertraline', 'generic_name' => 'Sertraline'],
            ['name' => 'Metronidazole', 'generic_name' => 'Metronidazole'],
            ['name' => 'Fluoxetine', 'generic_name' => 'Fluoxetine'],
            ['name' => 'Ibuprofen', 'generic_name' => 'Ibuprofen'],
            ['name' => 'Tramadol', 'generic_name' => 'Tramadol'],
            ['name' => 'Citalopram', 'generic_name' => 'Citalopram'],
            ['name' => 'Escitalopram', 'generic_name' => 'Escitalopram'],
            ['name' => 'Amoxicillin', 'generic_name' => 'Amoxicillin'],
            ['name' => 'Bupropion', 'generic_name' => 'Bupropion'],
            ['name' => 'Duloxetine', 'generic_name' => 'Duloxetine'],
            ['name' => 'Venlafaxine', 'generic_name' => 'Venlafaxine'],
            ['name' => 'Clonazepam', 'generic_name' => 'Clonazepam'],
            ['name' => 'Lorazepam', 'generic_name' => 'Lorazepam'],
            ['name' => 'Trazodone', 'generic_name' => 'Trazodone'],
            ['name' => 'Azithromycin', 'generic_name' => 'Azithromycin'],
            ['name' => 'Metoclopramide', 'generic_name' => 'Metoclopramide'],
            ['name' => 'Phenobarbital', 'generic_name' => 'Phenobarbital'],
            ['name' => 'Cephalexin', 'generic_name' => 'Cephalexin'],
            ['name' => 'Warfarin', 'generic_name' => 'Warfarin'],
            ['name' => 'Oxycodone', 'generic_name' => 'Oxycodone'],
            ['name' => 'Hydromorphone', 'generic_name' => 'Hydromorphone'],
            ['name' => 'Pregabalin', 'generic_name' => 'Pregabalin'],
            ['name' => 'Mirtazapine', 'generic_name' => 'Mirtazapine'],
            ['name' => 'Furosemide', 'generic_name' => 'Furosemide'],
            ['name' => 'Ranitidine', 'generic_name' => 'Ranitidine'],
            ['name' => 'Carbamazepine', 'generic_name' => 'Carbamazepine'],
            ['name' => 'Loratadine', 'generic_name' => 'Loratadine'],
            ['name' => 'Doxycycline', 'generic_name' => 'Doxycycline'],
            ['name' => 'Sildenafil', 'generic_name' => 'Sildenafil'],
            ['name' => 'Clarithromycin', 'generic_name' => 'Clarithromycin'],
            ['name' => 'Fentanyl', 'generic_name' => 'Fentanyl'],
            ['name' => 'Ciprofloxacin', 'generic_name' => 'Ciprofloxacin'],
            ['name' => 'Naproxen', 'generic_name' => 'Naproxen'],
            ['name' => 'Amphetamine', 'generic_name' => 'Amphetamine'],
            ['name' => 'Morphine', 'generic_name' => 'Morphine'],
            ['name' => 'Codeine', 'generic_name' => 'Codeine'],
            ['name' => 'Diazepam', 'generic_name' => 'Diazepam'],
            ['name' => 'Lansoprazole', 'generic_name' => 'Lansoprazole'],
        ];
        // Get all categories and suppliers
        $categories = Category::pluck('id')->toArray();
        $suppliers = Supplier::pluck('id')->toArray();

        // Loop to create 10 products with random data
        for ($i = 1; $i <= 50; $i++) {
            Product::create([
                'barcode' => rand(1000000000, 9999999999),
                'sku' => 'MED' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => $medicines[$i]['name'],
                'supplier_id' => $suppliers[array_rand($suppliers)],
                'category_id' => $categories[array_rand($categories)],
                'unit_id' => rand(1, 2),
                'generic_name' => $medicines[$i]['generic_name'],
                'strength' => rand(50, 200) . 'mg',
                'purchase_price' => rand(1, 10) + (rand(0, 99) / 100),
                'sale_price' => rand(10, 20) + (rand(0, 99) / 100),
                'tax' => rand(5, 15),
                'tax_value_type' => 'percentage',
            ]);
        }
    }
}
