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
            RoleSeeder::class,

            MasterData2022Seeder::class,
            MasterData2023Seeder::class,
            MasterData2024Seeder::class,

            DuplicateDataKinerja::class,
            DuplicateLKEKomponenSeeder::class,
            LKESeeder::class,
            Master2024::class,
            SubKegiatanDinasPendidikanSeeder::class,
            UpdateNomorLKESeeder::class,
            
            ValidatorLKESeeder::class,
            ValidatorPerencanaanSeeder::class,
            ViewRaporKinerjaUserSeeder::class,
        ]);
    }
}
