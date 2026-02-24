<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { debounce } from 'lodash'

export default {
  middleware: 'role-validator-bappeda',

  data() {
    return {
      isDebug: this.$route.query.debug == 1,
      data: {
        data: [],
        total: 0,
        per_page: 10,
        current_page: 1,
      },
      filter: {
        satuan_kerja_id: null,
        bulan: null,
        search: null,
      },
      tempSearch: '',
      isBusy: {
        getData: false,
        validate: false,
        validateAll: false,
      },
      table: {
        fields: [
          { key: 'no', label: 'No' },
          { key: 'kinerja_kegiatan', label: 'Sasaran Kegiatan' },
          { key: 'sasaran', label: 'Sasaran Sub Kegiatan' },
          { key: 'indikator', label: 'Indikator Sub Kegiatan' },
          { key: 'target_bulanan', label: 'Target' },
          { key: 'realisasi_bulanan', label: 'Realisasi' },
          { key: 'eviden_bulanan', label: 'Eviden' },
          { key: 'action', label: 'Validasi', thStyle: { width: '200px' }},
        ]
      },
    }
  },

  watch: {
    filter: {
      handler: function () {
        this.getData()
      },
      deep: true,
    },
    tempSearch: debounce(function (value) {
      this.filter.search = value
    }, 500),
  },

  methods: {
    async getData(page = 1) {
      if (!this.filter.satuan_kerja_id || !this.filter.bulan) {
        return
      }

      try {
        this.isBusy.getData = true

        const { data } = await axios.get('/kinerja-sub-kegiatan/validasi', {
          params: {
            page,
            ...this.filter,
          },
        })

        this.data = data
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: 'Gagal mengambil data',
        })
      } finally {
        this.isBusy.getData = false
      }
    },
    async validate(id, status) {
      try {
        this.isBusy.validate = true

        const data = this.data.data.find(item => item.id === id).validasi_bulanan[this.filter.bulan]
  
        await axios.post('/kinerja-sub-kegiatan/validasi', {
          id,
          status,
          catatan: data.catatan,
          bulan: this.filter.bulan,
        })
  
        this.getData(this.data.current_page)
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: 'Gagal validasi data',
        })
      } finally {
        this.isBusy.validate = false
      }
    },
    validateAllConfirm() {
      Swal.fire({
        title: 'Terima Semua',
        text:  'Apakah Anda yakin akan terima semua data? Data yang akan diterima adalah data yang tampil pada halaman saat ini dan belum divalidasi.',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Terima'
      })
      .then(({value}) => {
        if (value) {
          this.validateAll()
        }
      })
    },
    async validateAll() {
      const notValidated = this.data.data.filter(item => item.validasi_bulanan[this.filter.bulan].status === null)

      try {
        this.isBusy.validateAll = true

        await Promise.all(notValidated.map(async item => {
          const data = notValidated.find(d => d.id === item.id).validasi_bulanan[this.filter.bulan]
  
          await axios.post('/kinerja-sub-kegiatan/validasi', {
            id: item.id,
            status: true,
            catatan: data.catatan,
            bulan: this.filter.bulan,
          })
        }))

        this.getData(this.data.current_page)
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: 'Gagal validasi data',
        })
      } finally {
        this.isBusy.validateAll = false
      }
    }
  }
}
</script>

<template>
  <b-card>
    <b-row>
      <b-col>
        <FilterSatuanKerja v-model="filter.satuan_kerja_id" />
      </b-col>
      <b-col>
        <b-form-group label="Bulan" label-class="font-weight-bold">
          <b-form-select v-model="filter.bulan" :options="$const.months.map(month => ({value: month[0], text: month[1]}))"></b-form-select>
        </b-form-group>
      </b-col>
      <b-col>
        <b-form-group label="Cari" label-class="font-weight-bold">
          <b-form-input v-model="tempSearch" placeholder="Cari Sasaran Kegiatan/Sasaran Sub Kegiatan/Indikator Sub Kegiatan" />
        </b-form-group>
      </b-col>
    </b-row>

    <div class="text-right">
      <b-button variant="primary" @click="validateAllConfirm" :disabled="isBusy.validateAll">
        <b-spinner v-if="isBusy.validateAll" small></b-spinner>
        Terima Semua
      </b-button>
    </div>
    
    <b-table responsive hover :fields="table.fields" :items="data.data" :busy="isBusy.getData" show-empty class="table-bordered mt-4" head-variant="info">
      <template #cell(no)="row">
        {{ data.from + row.index }}
        <b-badge v-if="isDebug">ID {{ row.item.id }}</b-badge>
      </template>
      <template #cell(kinerja_kegiatan)="row">
        {{ row.value.sasaran || '-' }}
      </template>
      <template #cell(target_bulanan)="row">
        {{ row.value[filter.bulan] }} {{ row.item.satuan }}
      </template>
      <template #cell(realisasi_bulanan)="row">
        <span v-if="row.value[filter.bulan]">
          {{ row.value[filter.bulan] }} {{ row.item.satuan }}
        </span>
        <span v-else>-</span>
      </template>
      <template #cell(eviden_bulanan)="row">
        <a v-if="row.value[filter.bulan]" :href="row.value[filter.bulan]" target="_blank">Lihat eviden</a>
        <span v-else>-</span>
      </template>
      <template #cell(action)="row">
        <template v-if="row.item.validasi_bulanan[filter.bulan].status === null">
          <b-form-textarea
            v-model="row.item.validasi_bulanan[filter.bulan].catatan"
            placeholder="Catatan"
            rows="3"
          ></b-form-textarea>
          <div class="mt-2 text-center">
            <b-button @click="validate(row.item.id, true)" :disabled="isBusy.validate || isBusy.validateAll" variant="success" size="sm" class="m-1 rounded-circle" title="Terima">
              <i class="fa fa-check" aria-hidden="true"></i>
            </b-button>
            <b-button @click="validate(row.item.id, false)" :disabled="isBusy.validate || isBusy.validateAll" variant="danger" size="sm" class="m-1 rounded-circle" title="Tolak">
              <i class="fa fa-times" aria-hidden="true"></i>
            </b-button>
          </div>
        </template>
        <div v-else>
          <b-badge v-if="row.item.validasi_bulanan[filter.bulan].status" variant="success">Diterima</b-badge>
          <b-badge v-else variant="danger">Ditolak</b-badge>
          <div v-if="row.item.validasi_bulanan[filter.bulan].catatan" class="mt-2">
            <b>Catatan:</b> <div style="white-space: pre;">{{ row.item.validasi_bulanan[filter.bulan].catatan }}</div>
          </div>
        </div>
      </template>
    </b-table>

    <div>
      <b-pagination
        v-model="data.current_page"
        :total-rows="data.total"
        :per-page="data.per_page"
        @change="getData($event)"
      >
        <template #page="{ page, active }">
          <i class="fa fa-spinner fa-pulse fa-fw" v-if="isBusy.getData && active"></i>
          <template v-else>{{ page }}</template>
        </template>
      </b-pagination>
    </div>
  </b-card>
</template>