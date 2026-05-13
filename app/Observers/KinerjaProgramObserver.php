<?php
// app/Observers/KinerjaProgramObserver.php

namespace App\Observers;

use App\Models\KinerjaProgram;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaSubKegiatan;

class KinerjaProgramObserver
{
    public function creating(KinerjaProgram $kinerjaProgram): void
    {
        $this->updateIsSekretariat($kinerjaProgram);
    }

    public function updating(KinerjaProgram $kinerjaProgram): void
    {
        $this->updateIsSekretariat($kinerjaProgram);
    }

    public function updated(KinerjaProgram $kinerjaProgram): void
    {
        if ($kinerjaProgram->wasChanged('is_sekretariat')) {
            // Update cascade ke kinerja_kegiatan
            KinerjaKegiatan::where('kinerja_program_id', $kinerjaProgram->id)
                ->update(['is_sekretariat' => $kinerjaProgram->is_sekretariat]);
        }
    }

    private function updateIsSekretariat(KinerjaProgram $kinerjaProgram): void
    {
        if ($kinerjaProgram->pengampu === 'unit-kerja') {
            $kinerjaProgram->is_sekretariat = \DB::table('v_struktur_organisasi')
                ->where('id', $kinerjaProgram->v_struktur_organisasi_id)
                ->where('unit_kerja_nama', 'SEKRETARIAT')
                ->exists();
        } else {
            $kinerjaProgram->is_sekretariat = false;
        }
    }
}