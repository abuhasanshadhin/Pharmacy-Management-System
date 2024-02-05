<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::table('prescriptions')->insert([
                [
                    'no' => uniqid(),
                    'date' => now(),
                    'patient_name' => 'Jane Doe',
                    'patient_age' => 30,
                    'patient_phone' => '555-555-5555',
                    'patient_address' => '789 Oak St, Anytown, USA',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'no' => uniqid(),
                    'date' => now(),
                    'patient_name' => 'John Smith',
                    'patient_age' => 40,
                    'patient_phone' => '666-666-6666',
                    'patient_address' => '890 Maple St, Anytown, USA',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'no' => uniqid(),
                    'date' => now(),
                    'patient_name' => 'Emily Brown',
                    'patient_age' => 5,
                    'patient_phone' => '777-777-7777',
                    'patient_address' => '901 Birch St, Anytown, USA',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'no' => uniqid(),
                    'date' => now(),
                    'patient_name' => 'Michael Lee',
                    'patient_age' => 60,
                    'patient_phone' => '888-888-8888',
                    'patient_address' => '123 Cherry St, Anytown, USA',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'no' => uniqid(),
                    'date' => '2024-05-23',
                    'patient_name' => 'Sarah Johnson',
                    'patient_age' => 25,
                    'patient_phone' => '999-999-9999',
                    'patient_address' => '456 Chestnut St, Anytown, USA',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::info('Prescription Seeder Error: ' . $e->getMessage());
        }
    }
}
