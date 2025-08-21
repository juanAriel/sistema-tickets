<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['cajero', 'cliente'];

        foreach ($types as $type) {
            DB::table('counters')->updateOrInsert(
                ['type' => $type],
                ['last_number' => 0, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
