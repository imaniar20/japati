<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class ValidatorLKESeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['Agus Mirakusuma', 'validatorlke_agus_mirakusuma'],
            ['Andy', 'validatorlke_andy'],
            ['Denkom', 'validatorlke_denkom'],
            ['Densus', 'validatorlke_densus'],
            ['Dini Tresnawati', 'validatorlke_dini_tresnawati'],
            ['GP', 'validatorlke_gp'],
            ['Iis Solihat', 'validatorlke_iis_solihat'],
            ['Peri wahjurohim', 'validatorlke_peri_wahjurohim'],
            ['Roby', 'validatorlke_roby'],
            ['Sabar', 'validatorlke_sabar'],
            ['Sugeng', 'validatorlke_sugeng'],
            ['Titi Mugiati', 'validatorlke_titi_mugiati'],
            ['Tony Effendy', 'validatorlke_tony_effendy'],
            ['Toto Suarto', 'validatorlke_toto_suarto'],
        ];

        $pass = bcrypt('lkejabarjuara');

        foreach ($users as $user) {
            User::query()->updateOrCreate([
                'username' => $user[1],
            ], [
                'nama' => $user[0],
                'password' => $pass,
                'role_id' => Role::VALIDATOR_LKE,
            ]);
        }
    }
}
