<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class ViewRaporKinerjaUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            [
                'username' => 'anakin',
            ],
            [
                'nama' => 'Anakin',
                'password' => bcrypt('anakinsakip'),
                'role_id' => Role::VIEW_RAPOR_KINERJA,
            ]
        );
    }
}
