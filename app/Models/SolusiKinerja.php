<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolusiKinerja extends Model
{
    use HasFactory;

    protected $table = 'solusi_kinerja';

    protected $fillable = [
        'masalah_id',
        'masalah_type',
        'solusi_id',
        'solusi_type',
    ];

    protected $appends = ['masalah_string', 'solusi_string'];

    protected function masalahType(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => self::parseTypeClass($value),
        );
    }

    protected function solusiType(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => self::parseTypeClass($value),
        );
    }

    protected function masalahString(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => self::parseTypeClass($attributes['masalah_type'], false),
        );
    }

    protected function solusiString(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => self::parseTypeClass($attributes['solusi_type'], false),
        );
    }

    public static function parseTypeClass(string $type, bool $asNamespace = true)
    {
        if (in_array($type, ['kinerja-program', KinerjaProgram::class])) {
            return $asNamespace ? KinerjaProgram::class : 'kinerja-program';
        } elseif ((in_array($type, ['kinerja-kegiatan', KinerjaKegiatan::class]))) {
            return $asNamespace ? KinerjaKegiatan::class : 'kinerja-kegiatan';
        } elseif ((in_array($type, ['kinerja-sub-kegiatan', KinerjaSubKegiatan::class]))) {
            return $asNamespace ? KinerjaSubKegiatan::class : 'kinerja-sub-kegiatan';
        } elseif ((in_array($type, ['kinerja-langkah-aksi', KinerjaLangkahAksi::class]))) {
            return $asNamespace ? KinerjaLangkahAksi::class : 'kinerja-langkah-aksi';
        } else {
            throw new Exception('Type solusi-kinerja tidak valid');
        }
    }

    public function solusi()
    {
        return $this->morphTo('solusi', 'solusi_type', 'solusi_id');
    }

    public function masalah()
    {
        return $this->morphTo('masalah', 'masalah_type', 'masalah_id');
    }
}
