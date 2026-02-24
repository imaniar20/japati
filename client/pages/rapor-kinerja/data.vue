<script>
import axios from 'axios'
import TriwulanOption from '~/components/TriwulanOption.vue'

export default {
  middleware: ['auth'],

  components: {
    TriwulanOption,
  },

  async asyncData({ params, redirect }) {
    const triwulan = parseInt(params.triwulan)
    
    if (![1, 2, 3, 4].includes(triwulan)) {
      redirect('/rapor-kinerja/1/data')
      return false
    }

    return {
      triwulan,
    }
  },

  data() {
    let satkerId = null

    if (!this.$role.isSuper() && !this.$role.isviewRaporKinerja()) {
      satkerId = this.$store.getters['auth/user'].satuan_kerja_id
    }

    return {
      data: [],
      isBusy: {
        getData: false,
      },
      filter: {
        capaian: null,
        satuan_kerja_id: satkerId,
        is_external: 0,
      },
      options: {
        capaian: [
          { value: null, text: 'Semua' },
          { value: 'tercapai', text: 'Tercapai' },
          { value: 'tidak-tercapai', text: 'Tidak Tercapai' },
        ]
      },
      table: {
        fields: [
          { key: 'no', label: 'No' },
          // { key: 'kegiatan', label: 'Kegiatan' },
          { key: 'sasaran', label: 'Sasaran Sub Kegiatan' },
          // { key: 'sub_kegiatan', label: 'Sub Kegiatan' },
          { key: 'indikator', label: 'Indikator Sub Kegiatan' },
          { key: 'target_bulanan', label: 'Target Triwulan' },
          { key: 'realisasi_bulanan', label: 'Realisasi Triwulan' },
          { key: 'penyebab_kegagalan', label: 'Penyebab Kegagalan' },
        ]
      },
      triwulanBulan: {
        1: [['jan', 'Januari'], ['feb', 'Februari'], ['mar', 'Maret']],
        2: [['jan', 'Januari'], ['feb', 'Februari'], ['mar', 'Maret'], ['apr', 'April'], ['may', 'Mei'], ['jun', 'Juni']],
        3: [['jan', 'Januari'], ['feb', 'Februari'], ['mar', 'Maret'], ['apr', 'April'], ['may', 'Mei'], ['jun', 'Juni'], ['jul', 'Juli'], ['aug', 'Agustus'], ['sep', 'September']],
        4: [['jan', 'Januari'], ['feb', 'Februari'], ['mar', 'Maret'], ['apr', 'April'], ['may', 'Mei'], ['jun', 'Juni'], ['jul', 'Juli'], ['aug', 'Agustus'], ['sep', 'September'], ['oct', 'Oktober'], ['nov', 'November'], ['dec', 'Desember']],
      }
    }
  },

  watch: {
    triwulan: function (val) {
      this.$router.push(`/rapor-kinerja/${val}/data`)
    },
    filter: {
      handler: function (val) {
        if (val.satuan_kerja_id) {
          this.getData()
        }
      },
      deep: true,
    },
  },

  mounted() {
    if (this.filter.satuan_kerja_id) {
      this.getData()
    }
  },

  methods: {
    async getData() {
      this.isBusy.getData = true

      try {
        const { data } = await axios.get(`/rapor-kinerja/${this.triwulan}/data`, {
          params: this.filter
        })

        this.data = data
      } catch (error) {
        alert('Gagal mengambil data rapor kinerja')
      } finally {
        this.isBusy.getData = false
      }
    }
  }
}
</script>

<template>
  <div>
    <b-card class="mb-2">
      <TriwulanOption v-model="triwulan" />
    </b-card>
    <b-card>
      <b-row>
        <b-col cols="6">
          <b-form-group label="Capaian" label-class="font-weight-bold">
            <b-form-select v-model="filter.capaian" :options="options.capaian"></b-form-select>
          </b-form-group>
        </b-col>
        <b-col>
          <FilterSatuanKerja v-if="$role.isSuper() || $role.isviewRaporKinerja()" v-model="filter.satuan_kerja_id" labelTitle="Satuan Kerja" />
        </b-col>
      </b-row>

      <b-table responsive hover sticky-header="calc(100vh - 200px)" :fields="table.fields" :items="data" :busy="isBusy.getData" show-empty class="table-bordered" head-variant="info">
        <template #cell(no)="row">
          {{ row.index + 1 }}
        </template>
        <!-- <template #cell(kegiatan)="row">
          {{ row.value.nama || '-' }}
        </template> -->
        <!-- <template #cell(sub_kegiatan)="row">
          {{ row.value.nama || '-' }}
        </template> -->
        <template #cell(target_bulanan)="row">
          <div v-for="(bulan, index) of triwulanBulan[triwulan]" :key="index">
            {{ bulan[1] }}:&nbsp;{{ row.value[bulan[0]] }}
          </div>
        </template>
        <template #cell(realisasi_bulanan)="row">
          <div v-for="(bulan, index) of triwulanBulan[triwulan]" :key="index">
            {{ bulan[1] }}:&nbsp;{{ row.value[bulan[0]] }}
          </div>
        </template>
        <template #cell(penyebab_kegagalan)="row">
          <div v-if="!row.item.is_tercapai || (row.item.is_tercapai && row.item.penyebab_kegagalan_count)">Jumlah penyebab kegagalan: <b>{{ row.item.penyebab_kegagalan_count }}</b></div>
          <div v-if="!row.item.is_tercapai && !$role.isSuper() && !$role.isviewRaporKinerja()" class="text-center mt-2">
            <nuxt-link :to="`/rapor-kinerja/${triwulan}/data/${row.item.id}/penyebab-kegagalan`" class="btn btn-sm btn-primary" v-b-tooltip.hover title="Tambah penyebab kegagalan">
              <i class="fa fa-plus" aria-hidden="true"></i>
            </nuxt-link>
          </div>
        </template>
      </b-table>
    </b-card>
  </div>
</template>