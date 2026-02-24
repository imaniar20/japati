<?php

namespace App\Models\Simpeg;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VStrukturOrganisasi extends Model
{
    use HasFactory;

    protected $connection = 'simpeg';

    protected $table = 'v_struktur_organisasi';

    protected $primaryKey = 'id';

    // public function jf()
    // {
    //     return $this->belongsTo(JF::class, 'jf_id', 'jf_id');
    // }
}
