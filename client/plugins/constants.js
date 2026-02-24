/**
 * Beberapa isi file ini harus sinkron dengan supports\constants.php
 */

export default ({ app }, inject) => {
  /**
   * base tahun mulai
   * sinkron dengan supports\constants.php
   * jangan diubah!
   */
  const base_tahun_mulai = 2019;
  const base_tahun_mulai_2 = 20252;

  /**
   * default tahun_kinerja
   * ubah manual per tahun
   * sinkron dengan supports\constants.php
   * jangan panggil variabel ini, panggil helper getTahunKinerja() supaya dinamis berdasarkan filter tahun kinerja
   */
  const  tahun_kinerja= 2025;
  const tahun_kinerja_2 = 2025;

  inject('const', {
    tahun_mulai_list: [ // per 5 tahun
      2019,
      2024,
    ],
     tahun_kinerja_list: [
      { key: 2021, display: '2021' },
      { key: 2022, display: '2022' },
      { key: 2023, display: '2023' },
      { key: 2024, display: '2024' },
      { key: 2025, display: '2025' },
      { key: 20252, display: '2025  RPJMD Baru' }
    ],
    base_tahun_mulai,
    base_tahun_mulai_2,
    tahun_kinerja,
    months: [
      [ 'jan', 'Januari' ],
      [ 'feb', 'Februari' ],
      [ 'mar', 'Maret' ],
      [ 'apr', 'April' ],
      [ 'may', 'Mei' ],
      [ 'jun', 'Juni' ],
      [ 'jul', 'Juli' ],
      [ 'aug', 'Agustus' ],
      [ 'sep', 'September' ],
      [ 'oct', 'Oktober' ],
      [ 'nov', 'November' ],
      [ 'dec', 'Desember' ],
    ],
    SATKER_SETDA: 1001,
    SATKER_DPMPTSP: 1046,
  })
}