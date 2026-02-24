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
          { key: 'sasaran_strategis_pd', label: 'Sasaran Strategis' },
          { key: 'sasaran', label: 'Sasaran Program' },
          { key: 'indikator', label: 'Indikator Program' },
          { key: 'target', label: 'Target' },
          { key: 'realisasi', label: 'Realisasi' },
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
      if (!this.filter.satuan_kerja_id) {
        return
      }

      try {
        this.isBusy.getData = true

        const { data } = await axios.get('/kinerja-program/validasi', {
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

        const data = this.data.data.find(item => item.id === id).validasi_cascading
  
        await axios.post('/kinerja-program/validasi', {
          id,
          status,
          catatan: data.catatan,
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
      const notValidated = this.data.data.filter(item => item.validasi_cascading.status === null)

      try {
        this.isBusy.validateAll = true

        await Promise.all(notValidated.map(async item => {
          const data = notValidated.find(d => d.id === item.id).validasi_cascading
  
          await axios.post('/kinerja-program/validasi', {
            id: item.id,
            status: true,
            catatan: data.catatan,
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
        <b-form-group label="Cari" label-class="font-weight-bold">
          <b-form-input v-model="tempSearch" placeholder="Cari Sasaran Strategis/Sasaran Program/Indikator Program" />
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
      <template #cell(sasaran_strategis_pd)="row">
        {{ row.value.sasaran_strategis_satker || '-' }}
      </template>
      <template #cell(target)="row">
        {{ row.value }} {{ row.item.satuan }}
      </template>
      <template #cell(realisasi)="row">
        <span v-if="row.value">
          {{ row.value }} {{ row.item.satuan }}
        </span>
        <span v-else>-</span>
      </template>
      <template #cell(action)="row">
        <template v-if="row.item.validasi_cascading.status === null">
          <b-form-textarea
            v-model="row.item.validasi_cascading.catatan"
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
          <b-badge v-if="row.item.validasi_cascading.status" variant="success">Diterima</b-badge>
          <b-badge v-else variant="danger">Ditolak</b-badge>
          <div v-if="row.item.validasi_cascading.catatan" class="mt-2">
            <b>Catatan:</b> <div style="white-space: pre;">{{ row.item.validasi_cascading.catatan }}</div>
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