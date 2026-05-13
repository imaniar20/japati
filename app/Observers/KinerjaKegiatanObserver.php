<?php
// app/Observers/KinerjaKegiatanObserver.php

namespace App\Observers;

use App\Models\KinerjaKegiatan;
use App\Models\KinerjaSubKegiatan;

class KinerjaKegiatanObserver
{
    public function updated(KinerjaKegiatan $kinerjaKegiatan): void
    {
        if ($kinerjaKegiatan->wasChanged('is_sekretariat')) {
            // Update cascade ke kinerja_sub_kegiatan
            KinerjaSubKegiatan::where('kinerja_kegiatan_id', $kinerjaKegiatan->id)
                ->update(['is_sekretariat' => $kinerjaKegiatan->is_sekretariat]);
        }
    }
}