<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Role extends Model
{
    const SUPER = 1;

    const PEMERINTAH_DAERAH = 2; // user makro

    const PERANGKAT_DAERAH = 3; // user mikro

    const SETDA = 4;

    const GUBERNUR = 5;

    const PEMDA = 6;

    const VALIDATOR_BAPPEDA = 7;

    const VALIDATOR_LKE = 8;

    const VALIDATOR_LKE_PLENO = 9;

    const VALIDATOR_PERENCANAAN_1 = 10;

    const VALIDATOR_PERENCANAAN_2 = 11;

    const VALIDATOR_PERENCANAAN_3 = 12;

    const VIEW_RAPOR_KINERJA = 13;

    const VIEW_ALL = 14;

    const VALIDATOR_PENGAMPU = 15;

    const ROLES = [
        self::SUPER,
        self::PEMERINTAH_DAERAH,
        self::PERANGKAT_DAERAH,
        self::SETDA,
        self::GUBERNUR,
        self::PEMDA,
        self::VALIDATOR_BAPPEDA,
        self::VALIDATOR_LKE,
        self::VALIDATOR_LKE_PLENO,
    ];

    public static function isSuper()
    {
        return Auth::check() && Auth::user()->role_id === self::SUPER;
    }

    public static function isPemerintahDaerah()
    {
        return Auth::check() && Auth::user()->role_id === self::PEMERINTAH_DAERAH;
    }

    public static function isPerangkatDaerah()
    {
        return Auth::check() && Auth::user()->role_id === self::PERANGKAT_DAERAH;
    }

    public static function isSetda()
    {
        return Auth::check() && Auth::user()->role_id === self::SETDA;
    }

    public static function isGubernur()
    {
        return Auth::check() && Auth::user()->role_id === self::GUBERNUR;
    }

    public static function isGuest()
    {
        return ! Auth::check();
    }

    public static function isPemda()
    {
        return Auth::check() && Auth::user()->role_id === self::PEMDA;
    }

    public static function isValidatorPerencanaan1()
    {
        return Auth::check() && Auth::user()->role_id === self::VALIDATOR_PERENCANAAN_1;
    }

    public static function isValidatorPerencanaan2()
    {
        return Auth::check() && Auth::user()->role_id === self::VALIDATOR_PERENCANAAN_2;
    }

    public static function isValidatorPerencanaan3()
    {
        return Auth::check() && Auth::user()->role_id === self::VALIDATOR_PERENCANAAN_3;
    }

    public static function isviewRaporKinerja()
    {
        return Auth::check() && Auth::user()->role_id === self::VIEW_RAPOR_KINERJA;
    }

    public static function isViewAll()
    {
        return Auth::check() && Auth::user()->role_id === self::VIEW_ALL;
    }
}
