<?php

use App\Models\LKE\Eviden;
use App\Models\LKE\RiwayatEviden;

function getIp()
{
    foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip); // just to be safe
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }
}

/**
 * Ambil satuan kerja, jika setda maka akan return semua biro,
 * jika bukan maka akan return sesuai parameter
 */
function getSatuanKerjaIds(int $satkerId): array
{
    // jika satkerId = setda, maka ambil setda + semua biro
    if ($satkerId == SATKER_SETDA) {
        return [
            SATKER_SETDA,
            100103020000, // biro adpim
            100102030000, // biro barjas
            100102020000, // biro bumd
            100101020000, // biro hukumham
            100101030000, // biro kesra
            100103010000, // biro organisasi
            100101010000, // biro pemotda
            100102010000, // biro perekonomian
            100103030000, // biro umum
        ];
    }

    return [$satkerId];
}

function setTahunKinerja(int $tahun): void
{
    session([
        'tahun_kinerja' => $tahun,
        'tahun_kinerja_2' => $tahun,
    ]);

    setEkinerjaDBTahun($tahun);
}

/**
 * get tahun kinerja berdasarkan session tahun kinerja
 * jika tidak ada ambil dari konstanta TAHUN_KINERJA
 */
function getTahunKinerja(): int
{
    if (strlen(session('tahun_kinerja', TAHUN_KINERJA)) == 4) {
        return session('tahun_kinerja', TAHUN_KINERJA);
    } else {
        return (int) substr(session('tahun_kinerja', TAHUN_KINERJA), 0, 4);
    }

}

/**
 * get tahun mulai berdasarkan getTahunKinerja()
 */
function getTahunMulai(): int
{
    $tahunKinerja = getTahunKinerja();
    $baseTahunMulaiBaru = (int) substr((string) BASE_TAHUN_MULAI_2, 0, 4);

    $tahunMulai = $tahunKinerja >= $baseTahunMulaiBaru
        ? $baseTahunMulaiBaru
        : BASE_TAHUN_MULAI;

    while ($tahunMulai < $tahunKinerja) {
        $tahunMulai += 5; // per 5 tahun
    }

    if (($tahunMulai - $tahunKinerja) % 5 === 0) {
        return $tahunMulai;
    }

    return $tahunMulai - 5;
}

function getKeyTahun(string $key, int $offset = 0): string
{
    if (strlen(getTahunKinerja()) == 4) {
        $index = (getTahunKinerja() - getTahunMulai()) + 1 + $offset;
        if ($index < 1) {
            $index = 'baseline';
        }

        return "{$key}_{$index}";
    } else {
        $tahunKinerja = (int) substr(getTahunKinerja(), 0, 4);
        $tahunMulai = (int) substr(getTahunMulai(), 0, 4);
        $index = ($tahunKinerja - $tahunMulai) + 1 + $offset;
        if ($index < 1) {
            $index = 'baseline';
        }

        return "{$key}_{$index}";
    }

}

/**
 * get satuan_kerja_id dengan mengecek apakah biro atau bukan,
 * jika biro maka akan return satuan kerja id setda,
 * jika bukan biro maka akan return sesuai parameter
 */
function parseSatuanKerjaId(int $satkerId): int
{
    return isBiro($satkerId) ? SATKER_SETDA : $satkerId;
}

/**
 * Cek apakah biro berdasarkan satuan kerja id
 */
function isBiro(?int $satkerId): bool
{
    return $satkerId != SATKER_SETDA && isBiroOrSetda($satkerId);
}

/**
 * Cek apakah biro atau setda berdasarkan satuan kerja id
 */
function isBiroOrSetda(?int $satkerId): bool
{
    return substr($satkerId, 0, 4) == SATKER_SETDA;
}

/**
 * Generate `role` middleware by `role_id`
 */
function roleMiddleware(int|array $roles): string
{
    $roles = is_array($roles) ? $roles : func_get_args();
    $roles = implode(',', $roles);

    return "role:{$roles}";
}

function setEkinerjaDBTahun(int $tahun): void
{
    if (strlen($tahun) == 4) {
        config([
            'database.connections.ekinerja' => config("database.connections.ekinerja_{$tahun}"),
            'database.connections.pgsql' => config('database.connections.pgsql2'),
        ]);
    } else {
        $tahun = substr($tahun, 0, 4);
        config([
            'database.connections.ekinerja' => config("database.connections.ekinerja_{$tahun}"),
            'database.connections.pgsql' => config('database.connections.pgsql'),
        ]);
    }

}

function removeSpecialCharacters($string)
{
    // Use preg_replace to remove special characters
    $cleanedString = preg_replace('/[^A-Za-z0-9\s]/', '', $string);

    return $cleanedString;
}

function getAlphabetLetter($index)
{
    $alphabet = range('a', 'z');

    return isset($alphabet[$index]) ? $alphabet[$index] : '';
}

function capitalizeWords($string)
{
    $string = strtolower($string);

    return ucwords($string);
}

function getDataCatatanInProcess($satuanKerja, $tahun, $nomor)
{
    return Eviden::with(
        [
            'kriteria',
            'kriteria.subKomponen',
            'kriteria.subKomponen.komponen',
        ])

        ->where('satuan_kerja_id', '=', $satuanKerja->satuan_kerja_id)
        ->whereHas('kriteria.subKomponen.komponen', function ($query) use ($tahun, $nomor) {
            $query
                ->where('tahun_kinerja', '=', $tahun)
                ->where('nomor', '=', $nomor);
        })
        ->get();
}
function getDataCatatan($satuanKerja, $tahun, $nomor, $statusPenilaian)
{
    return RiwayatEviden::with(
        [
            'penilaian',
            'riwayat',
            'riwayat.kriteria',
            'riwayat.kriteria.subKomponen',
            'riwayat.kriteria.subKomponen.komponen',
        ])
        ->whereHas('penilaian', function ($query) use ($satuanKerja, $statusPenilaian) {
            $query
                ->where('satuan_kerja_id', '=', $satuanKerja->satuan_kerja_id)
                ->whereNotNull('catatan')
                ->where('status', $statusPenilaian);
        })
        ->whereHas('riwayat.kriteria.subKomponen.komponen', function ($query) use ($tahun, $nomor) {
            $query
                ->where('tahun_kinerja', '=', $tahun)
                ->where('nomor', '=', $nomor);
        })
        ->where('status', '=', false)
        ->get();
}

function removeFirstCharacter($string)
{
    // Check if the string is not empty
    if (strlen($string) > 0) {
        // Use substr to remove the first character
        $string = str_replace(["\r", "\n"], '', $string);

        return substr($string, 1);
    }

    return $string; // Return the original string if it's empty
}
