<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { debounce } from 'lodash'

export default {
  middleware: 'role-validator-pengampu',

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
          { key: 'kegiatan', label: 'Kegiatan' },
          { key: 'sasaran', label: 'Sasaran' },
          { key: 'indikator', label: 'Indikator' },
          { key: 'sub_kegiatan', label: 'Sub Kegiatan' },
          { key: 'pengampu', label: 'Pengampu' },
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

        const { data } = await axios.get('/kinerja-sub-kegiatan/validasi-pengampu', {
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

        const data = this.data.data.find(item => item.id === id).validasi_pengampu
  
        await axios.post('/kinerja-sub-kegiatan/validasi-pengampu', {
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
      const notValidated = this.data.data.filter(item => item.validasi_pengampu.status === null)

      try {
        this.isBusy.validateAll = true

        await Promise.all(notValidated.map(async item => {
          const data = notValidated.find(d => d.id === item.id).validasi_pengampu
  
          await axios.post('/kinerja-sub-kegiatan/validasi-pengampu', {
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
          <b-form-input v-model="tempSearch" placeholder="Cari Sub Kegiatan/Sasaran/Indikator" />
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
      <template #cell(kegiatan)="row">
        {{ row.value.nama || '-' }}
      </template>
      <template #cell(sub_kegiatan)="row">
        {{ row.value.nama || '-' }}
      </template>
      <template #cell(pengampu)="row">
        <div v-if="row.item.pengampu == 'unit-kerja' && row.item.struktur_organisasi">
          {{ row.item.struktur_organisasi.jabatan_nama }}
        </div>
        <div v-else-if="row.item.pengampu == 'tim-kerja' && row.item.tim_kerja">
          {{ row.item.tim_kerja.nama }} - {{ row.item.tim_kerja.ketua?.peg_nama }}
        </div>
        <div v-else>-</div>
      </template>
      <template #cell(action)="row">
        <template v-if="row.item.validasi_pengampu.status === null">
          <b-form-textarea
            v-model="row.item.validasi_pengampu.catatan"
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
          <b-badge v-if="row.item.validasi_pengampu.status" variant="success">Diterima</b-badge>
          <b-badge v-else variant="danger">Ditolak</b-badge>
          <div v-if="row.item.validasi_pengampu.catatan" class="mt-2">
            <b>Catatan:</b> <div style="white-space: pre;">{{ row.item.validasi_pengampu.catatan }}</div>
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