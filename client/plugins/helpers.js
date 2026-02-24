import Cookies from 'js-cookie'

export default ({ app, $const }, inject) => {
  const getTahunKinerja = () => {
    return parseInt(app.store.state['tahun-kinerja'].tahunKinerja || Cookies.get(process.env.tahunKinerjaKey) || $const.tahun_kinerja)
  }

  const getTahunKinerjaPublic = () => {
    return parseInt(app.store.state['tahun-kinerja'].tahunKinerjaPublic || Cookies.get(process.env.tahunKinerjaPublicKey) || $const.tahun_kinerja)
  }

  const getTahunMulaiByTahunKinerja = (tahunKinerja) => {
    if (String(tahunKinerja).length === 4) {
        let tahunMulai = $const.base_tahun_mulai;
        while (tahunMulai < tahunKinerja) {
            tahunMulai += 5; // per 5 tahun
        }
        if ((tahunMulai - tahunKinerja) % 5 === 0) {
            return tahunMulai;
        }
        // console.log(tahunMulai)
        return tahunMulai - 5;
    } else {
        let tahunMulai = parseInt(String($const.base_tahun_mulai_2).substring(0,4));
        let tahunKinerjaValue = parseInt(String(getTahunKinerja()).substring(0,4));
        while (tahunMulai < tahunKinerjaValue) {
            tahunMulai += 5; // per 5 tahun
        }

        if ((tahunMulai - tahunKinerjaValue) % 5 === 0) {
            return tahunMulai;
        }
        return tahunMulai - 5;
    }
}


  const getTahunMulai = () => {
    return getTahunMulaiByTahunKinerja(getTahunKinerja())
  }

  const getTahunMulaiPublic = () => {
    return getTahunMulaiByTahunKinerja(getTahunKinerjaPublic())
  }

  const getKeyTahun = (key, offset = 0) => {
    let index = (getTahunKinerja() - getTahunMulai()) + 1 + offset

    if (index < 1) {
      index = 'baseline'
    }

    return `${key}_${index}`
  }

  const getKeyTahunPublic = (key, offset = 0) => {
    let index = (getTahunKinerjaPublic() - getTahunMulaiPublic()) + 1 + offset

    if (index < 1) {
      index = 'baseline'
    }

    return `${key}_${index}`
  }

  const getKeyTahunDinamis = (key, tahun) => {
    const offset = tahun - getTahunKinerja()
    return getKeyTahun(key, offset)
  }

  const getKeyTahunDinamisPublic = (key, tahun) => {
    const offset = tahun - getTahunKinerjaPublic()
    
    return getKeyTahunPublic(key, offset)
  }

  /**
   * Mulai dari tahun kinerja 2023,
   * auto fill data kinerja
   * - anggaran
   * - target
   * - satuan
   */
  const isAutoFillKinerja = () => {
    return getTahunKinerja() >= 2023
  }

  const vSelectFilterBy = (...keys) => {
    return (option, label, search) => {
      let haystack = ''

      keys.forEach(key => {
        haystack += key.split('.').reduce((r, val) => r ? r[val] : '', option)
      })

      return haystack.toLocaleLowerCase().includes(search.toLocaleLowerCase())
    }
  }

  const setTahunKinerja = (tahun) => {
    app.store.dispatch('tahun-kinerja/setTahunKinerja', tahun)
  }

  const setTahunKinerjaPublic = (tahun) => {
    app.store.dispatch('tahun-kinerja/setTahunKinerjaPublic', tahun)
  }

  inject('helper', {
    getTahunMulaiByTahunKinerja,
    getKeyTahun,
    getKeyTahunPublic,
    getKeyTahunDinamis,
    getKeyTahunDinamisPublic,
    getTahunKinerja,
    getTahunMulai,
    getTahunKinerjaPublic,
    getTahunMulaiPublic,
    isAutoFillKinerja,
    vSelectFilterBy,
    setTahunKinerja,
    setTahunKinerjaPublic,
  })
}