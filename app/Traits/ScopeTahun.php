<?php

namespace App\Traits;

trait ScopeTahun
{
    public function scopeTahunMulai($query, $tahun = null)
    {
        return $query->where("{$this->table}.tahun_mulai", $tahun ?? getTahunMulai());
    }

    public function scopeTahunKinerja($query, $tahun = null)
    {
        return $query->where("{$this->table}.tahun_kinerja", $tahun ?? getTahunKinerja());
    }
}
