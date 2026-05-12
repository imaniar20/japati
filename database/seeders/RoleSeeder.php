<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $roles = [
            [
                'id' => 1,
                'name' => 'SUPER',
                'keterangan' => '-',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'PEMERINTAH_DAERAH',
                'keterangan' => '-',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'PERANGKAT_DAERAH',
                'keterangan' => '-',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'name' => 'SETDA',
                'keterangan' => '-',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'name' => 'GUBERNUR',
                'keterangan' => '-',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'name' => 'PEMDA',
                'keterangan' => '-',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 7,
                'name' => 'VALIDATOR_BAPPEDA',
                'keterangan' => '-',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 8,
                'name' => 'VALIDATOR_LKE',
                'keterangan' => '-',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 9,
                'name' => 'VALIDATOR_LKE_PLENO',
                'keterangan' => '-',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 10,
                'name' => 'VALIDATOR_PERENCANAAN_1',
                'keterangan' => '-',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 11,
                'name' => 'VALIDATOR_PERENCANAAN_2',
                'keterangan' => '-',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 12,
                'name' => 'VALIDATOR_PERENCANAAN_3',
                'keterangan' => '-',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 13,
                'name' => 'VIEW_RAPOR_KINERJA',
                'keterangan' => '-',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 14,
                'name' => 'VIEW_ALL',
                'keterangan' => '-',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 15,
                'name' => 'VALIDATOR_PENGAMPU',
                'keterangan' => '-',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}