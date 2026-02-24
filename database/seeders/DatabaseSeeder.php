<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\MasterData2022Seeder;
use Database\Seeders\MasterData2023Seeder;
use Database\Seeders\MasterData2024Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MasterData2022Seeder::class,
            MasterData2023Seeder::class,
            MasterData2024Seeder::class,
        ]);
    }
}
