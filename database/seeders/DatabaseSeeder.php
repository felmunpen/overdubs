<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $file_path = resource_path('overdubs_seed.sql');

        \DB::unprepared(
            file_get_contents($file_path)
        );
    }
}
