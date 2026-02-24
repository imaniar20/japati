<?php

/**
 * Beberapa isi gile ini harus sinkron dengan client\plugins\constants.js
 */

/**
 * base tahun mulai
 * sinkron dengan client\plugins\constants.js
 * jangan diubah!
 */
const BASE_TAHUN_MULAI = 2019;
const BASE_TAHUN_MULAI_2 = 2025;

/**
 * default tahun_kinerja
 * ubah manual per tahun
 * sinkron client\plugins\constants.js
 * jangan panggil variabel ini, panggil helper getTahunKinerja() supaya dinamis berdasarkan filter tahun kinerja
 */
const TAHUN_KINERJA = 2025;

const SATKER_SETDA = 1001;
const SATKER_SETWAN = 1002;
const SATKER_DINKES = 1004;
const SATKER_DISDUKCAPIL = 1023;
const SATKER_DPMPTSP = 1046;
const SATKER_BANHUB = 1043;

/**
 * @var array<int, array<string>>
 */
const TRIWULAN_BULAN = [
    1 => ['jan', 'feb', 'mar'],
    2 => ['jan', 'feb', 'mar', 'apr', 'may', 'jun'],
    3 => ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep'],
    4 => ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'],
];

const MONTHS = [
    ['jan', 'Januari'],
    ['feb', 'Februari'],
    ['mar', 'Maret'],
    ['apr', 'April'],
    ['may', 'Mei'],
    ['jun', 'Juni'],
    ['jul', 'Juli'],
    ['aug', 'Agustus'],
    ['sep', 'September'],
    ['oct', 'Oktober'],
    ['nov', 'November'],
    ['dec', 'Desember'],
];
