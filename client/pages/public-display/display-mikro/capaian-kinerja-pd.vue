<script>
import axios from 'axios';

export default {
  layout: 'guest',

  data() {
    return {
      data: {
        data: [],
        from: 0,
        current_page: 1,
        total: 0,
        per_page: 0,
      },
      filter: {
        satuan_kerja_id: null,
      },
      isBusy: false,
    }
  },

  mounted() {
    if (this.$route.query.tahun_kinerja) {
      this.$helper.setTahunKinerjaPublic(this.$route.query.tahun_kinerja)
    }

    this.filter.satuan_kerja_id = Number(this.$route.query.satuan_kerja_id) || null

    this.doFilter()
  },

  watch: {
    filter: {
      handler: function () {
        this.doFilter()
      },
      deep: true,
    }
  },

  methods: {
    parsePeningkatan(item, key) {
      const sekarang = item[this.$helper.getKeyTahunPublic(key)]
      const sebelum = item[this.$helper.getKeyTahunPublic(key, -1)]

      if (!sekarang || !sebelum) return '-';

      return (sekarang - sebelum).toFixed(2)
    },
    async doFilter(page = 1) {
      this.isBusy = true

      const { data } = await axios.get('/public-display/display-mikro/capaian-kinerja-pd', {
        params: {
          filter: this.filter,
          page,
        }
      })

      this.data = data
      this.isBusy = false
    },
    compareRataNasional(rataNasional, realisasi) {
      if (!rataNasional || !realisasi) return {
        text: null,
      }

      if (realisasi > rataNasional) {
        return {
          text: 'Lebih tinggi',
          nilai: realisasi - rataNasional,
          class: 'text-success'
        }
      } else if (realisasi < rataNasional) {
        return {
          text: 'Lebih rendah',
          nilai: rataNasional - realisasi,
          class: 'text-danger'
        }
      } else {
        return {
          text: 'Sama',
          nilai: null,
          class: 'text-secondary'
        }
      }
    },
    compareRataInternasional(rataInternasional, realisasi) {
      if (!rataInternasional || !realisasi) return {
        text: null,
      }

      if (realisasi > rataInternasional) {
        return {
          text: 'Lebih tinggi',
          nilai: realisasi - rataInternasional,
          class: 'text-success'
        }
      } else if (realisasi < rataInternasional) {
        return {
          text: 'Lebih rendah',
          nilai: rataInternasional - realisasi,
          class: 'text-danger'
        }
      } else {
        return {
          text: 'Sama',
          nilai: null,
          class: 'text-secondary'
        }
      }
    },
  },
}
</script>

<template>
  <b-card>
    <div>
      <b-row>
        <b-col sm="6" md="4">
          <FilterSatuanKerja v-model="filter.satuan_kerja_id" />
        </b-col>
      </b-row>
    </div>

    <b-table-simple sticky-header="calc(100vh - 0px)" :aria-busy="isBusy" hover responsive bordered striped>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle" rowspan="3">No</b-th>
          <b-th class="align-middle" rowspan="3">Satuan Kerja</b-th>
          <b-th class="align-middle" rowspan="3">Sasaran</b-th>
          <b-th class="align-middle" rowspan="3">Indikator</b-th>
          <b-th class="align-middle" colspan="3">P1</b-th>
          <b-th class="align-middle" colspan="6">P2</b-th>
          <b-th class="align-middle" colspan="2">P3</b-th>
          <b-th class="align-middle" colspan="3">P4</b-th>
          <b-th class="align-middle" colspan="3" rowspan="2">Perbandingan dengan Internasional</b-th>
          <b-th class="align-middle" rowspan="3">Penghargaan</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:45px" class="align-middle" colspan="3">Capaian Tahun {{ $helper.getTahunKinerjaPublic() }}</b-th>
          <b-th style="top:45px" class="align-middle" colspan="6">Peningkatan dari Tahun Lalu</b-th>
          <b-th style="top:45px" class="align-middle" colspan="2"><span style="min-width:200px;display:inline-block;">Capaian Tahun {{ $helper.getTahunKinerjaPublic() }} Terhadap Target Akhir Renstra</span></b-th>
          <b-th style="top:45px" class="align-middle" colspan="3">Perbandingan Dengan Nasional</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:110px" class="align-middle">Target</b-th>
          <b-th style="top:110px" class="align-middle">Realisasi</b-th>
          <b-th style="top:110px" class="align-middle">% Capaian</b-th>

          <b-th style="top:110px" class="align-middle">Realisasi {{ $helper.getTahunKinerjaPublic() }}</b-th>
          <b-th style="top:110px" class="align-middle">Realisasi Tahun lalu</b-th>
          <b-th style="top:110px" class="align-middle">Perbandingan realisasi dari tahun lalu</b-th>
          <b-th style="top:110px" class="align-middle">Capaian {{ $helper.getTahunKinerjaPublic() }}</b-th>
          <b-th style="top:110px" class="align-middle">Capaian Tahun Lalu</b-th>
          <b-th style="top:110px" class="align-middle">Peningkatan Capaian dari Tahun Lalu</b-th>

          <b-th style="top:110px" class="align-middle">Target akhir Renstra</b-th>
          <b-th style="top:110px" class="align-middle"><span style="min-width:115px;display:inline-block;">Realisasi Tahun {{ $helper.getTahunKinerjaPublic() }} Terhadap Target Akhir Renstra</span></b-th>
          
          <b-th style="top:110px" class="align-middle">Rata-Rata Realisasi Nasional</b-th>
          <b-th style="top:110px" class="align-middle">Perbandingan Nasional</b-th>
          <b-th style="top:110px" class="align-middle">Peringkat di Tingkat Nasional</b-th>

          <b-th style="top:110px" class="align-middle">Rata-Rata Realisasi Internasional</b-th>
          <b-th style="top:110px" class="align-middle">Perbandingan Internasional</b-th>
          <b-th style="top:110px" class="align-middle">Peringkat di Tingkat Internasional</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:210px" v-for="n of 22" :key="n">{{ n }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="!data.data.length">
          <b-td colspan="18">There are no records to show</b-td>
        </b-tr>
        <b-tr v-for="(item, index) of data.data" :key="item.id">
          <td>{{ data.from + index }}</td>
          <td>{{ item.satuan_kerja.satuan_kerja_nama }}</td>
          <b-td>{{ item.sasaran_strategis_satker }}</b-td>
          <b-td>{{ item.iku }}</b-td>
          <b-td class="text-center">{{ item[$helper.getKeyTahunPublic('target')] || '-' }}</b-td>
          <b-td class="text-center">{{ item[$helper.getKeyTahunPublic('realisasi')] || '-' }}</b-td>
          <b-td class="text-center">{{ item[$helper.getKeyTahunPublic('capaian')] || '-' }}</b-td>

          <!-- tahun sekarang -->
          <b-td class="text-center">{{ item[$helper.getKeyTahunPublic('realisasi')] || '-' }}</b-td>
          <!-- 1 tahun sebelumnya -->
          <b-td class="text-center">{{ item[$helper.getKeyTahunPublic('realisasi', -1)] || '-' }}</b-td>
          <b-td class="text-center">{{ parsePeningkatan(item, 'realisasi') }}</b-td>

          <!-- tahun sekarang -->
          <b-td class="text-center">{{ item[$helper.getKeyTahunPublic('capaian')] || '-' }}</b-td>
          <!-- 1 tahun sebelumnya -->
          <b-td class="text-center">{{ item[$helper.getKeyTahunPublic('capaian', -1)] || '-' }}</b-td>
          <b-td class="text-center">{{ parsePeningkatan(item, 'capaian') }}</b-td>

          <b-td class="text-center">{{ item.target_5 }}</b-td>
          <b-td class="text-center">
            <span v-if="item[$helper.getKeyTahunPublic('realisasi')]">
              {{ (item[$helper.getKeyTahunPublic('realisasi')] / item.target_5).toFixed(2) }}
            </span>
            <span v-else>-</span>
          </b-td>

          <b-td class="text-center">{{ item.rata_nasional }}</b-td>
          <b-td class="text-center">
            <span v-if="!compareRataNasional(item.rata_nasional, item[$helper.getKeyTahunPublic('realisasi')]).text">Belum ada data dari rata-rata realisasi nasional atau capaian</span>
            <span v-else>{{ compareRataNasional(item.rata_nasional, item[$helper.getKeyTahunPublic('realisasi')]).text }} <span class="font-weight-bold" :class="compareRataNasional(item.rata_nasional, item[$helper.getKeyTahunPublic('realisasi')]).class">{{ compareRataNasional(item.rata_nasional, item[$helper.getKeyTahunPublic('realisasi')]).nilai | decimalDigit}}</span> dari <br> Rata-Rata Realisasi Nasional </span>
          </b-td>
          <b-td class="text-center">{{ item.peringkat_nasional }}</b-td>

          <b-td class="text-center">{{ item.rata_internasional }}</b-td>
          <b-td class="text-center">
            <span v-if="!compareRataInternasional(item.rata_internasional, item[$helper.getKeyTahunPublic('realisasi')]).text">Belum ada data dari rata-rata realisasi internasional atau capaian</span>
            <span v-else>{{ compareRataInternasional(item.rata_internasional, item[$helper.getKeyTahunPublic('realisasi')]).text }} <span class="font-weight-bold" :class="compareRataInternasional(item.rata_internasional, item[$helper.getKeyTahunPublic('realisasi')]).class">{{ compareRataInternasional(item.rata_internasional, item[$helper.getKeyTahunPublic('realisasi')]).nilai | decimalDigit}}</span> dari <br> Rata-Rata Realisasi Internasional </span>
          </b-td>
          <b-td class="text-center">{{ item.peringkat_internasional }}</b-td>

          <b-td class="text-center">{{ item.redaksi }}</b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>

    <div>
      <b-pagination
        v-model="data.current_page"
        :total-rows="data.total"
        :per-page="data.per_page"
        @change="doFilter($event)"
      >
        <template #page="{ page, active }">
          <i class="fa fa-spinner fa-pulse fa-fw" v-if="isBusy && active"></i>
          <template v-else>{{ page }}</template>
        </template>
      </b-pagination>
    </div>
  </b-card>
</template>

<style scoped>
  .b-table-sticky-header > .table.b-table > thead > tr > th {
    font-size: inherit !important;
}
</style>
