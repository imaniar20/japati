<?php

namespace App\Models\Ekinerja;

use App\Models\SatuanKerja;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PegawaiData extends Model
{
    use HasFactory;

    protected $connection = 'ekinerja';

    protected $table = 'pegawai_data';

    protected $fillable = [
        'peg_nip',
        'id_satuan_kerja',
        'peg_nama',
        'jabatan_nama',
        'unit_kerja_nama',
        'peg_status',
    ];

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'id_satuan_kerja', 'satuan_kerja_id');
    }

    public function timKerja()
    {
        return $this->hasMany(TimKerja::class, 'nip_ketua', 'peg_nip');
    }
}
