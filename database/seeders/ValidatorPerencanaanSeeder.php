<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class ValidatorPerencanaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pass = bcrypt('validatorperencanaan');

        $tahap1 = [
            [
                'nama' => 'Dra. Ani Sri Mulyani,M.M.Pd.',
                'username' => 'validator_perencanaan_ani_sri_mulyani',
            ],
            [
                'nama' => 'Dra. Heni Fajria Rif\'ati, M.Hum',
                'username' => 'validator_perencanaan_heni_fajria_rifati',
            ],
            [
                'nama' => 'Dra. Reni Marlina, M.Si',
                'username' => 'validator_perencanaan_reni_marlina',
            ],
            [
                'nama' => 'Dra. Sri Endang Wijayanti, S.P., M.Si',
                'username' => 'validator_perencanaan_sri_endang_wijayanti',
            ],
            [
                'nama' => 'Dwi Astuti Ruhayati, S.Si., M.T',
                'username' => 'validator_perencanaan_dwi_astuti_ruhayati',
            ],
            [
                'nama' => 'Firman Firdaus Sendjaya, S.IP, M.AP',
                'username' => 'validator_perencanaan_firman_firdaus_sendjaya',
            ],
            [
                'nama' => 'HJ. Hermi Harini, SE',
                'username' => 'validator_perencanaan_hermi_harini',
            ],
            [
                'nama' => 'Ir. Dewi Nurhayati, M.Si',
                'username' => 'validator_perencanaan_dewi_nurhayati',
            ],
            [
                'nama' => 'Ir. H. Latief, MM',
                'username' => 'validator_perencanaan_latief',
            ],
            [
                'nama' => 'Nenden Suwardini, SE., ST., MT',
                'username' => 'validator_perencanaan_nenden_suwardini',
            ],
            [
                'nama' => 'Reny Welyindra K., SH., M.Si',
                'username' => 'validator_perencanaan_reny_welyindra_k',
            ],
            [
                'nama' => 'Sutrisno, ST., MT',
                'username' => 'validator_perencanaan_sutrisno',
            ],
        ];

        foreach ($tahap1 as $user) {
            User::query()->updateOrCreate([
                'username' => $user['username'],
            ], [
                'nama' => $user['nama'],
                'password' => $pass,
                'role_id' => Role::VALIDATOR_PERENCANAAN_1,
            ]);
        }

        User::query()->updateOrCreate([
            'username' => 'validator_perencanaan_bpkad',
        ], [
            'nama' => 'Validator Perencanaan BPKAD',
            'password' => $pass,
            'role_id' => Role::VALIDATOR_PERENCANAAN_2,
        ]);

        User::query()->updateOrCreate([
            'username' => 'validator_perencanaan_bkd',
        ], [
            'nama' => 'Validator Perencanaan BKD',
            'password' => $pass,
            'role_id' => Role::VALIDATOR_PERENCANAAN_3,
        ]);
    }
}
