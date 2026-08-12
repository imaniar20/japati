<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { destroy as doDestroy } from '~/plugins/swal'

export default {
  middleware: ['auth', 'role-perangkat-daerah'],

  data() {
    return {
      data: [],
      selectedIds: [],
      selectAll: false,
      filter: {
        keyword: '',
        satuan_kerja_id: null,
        tahun_kinerja: this.$helper.getTahunKinerja(),
      },
      isBusy: {
        getData: false,
      },
      importModal: {
        show: false,
        file: null,
        items: [],
        isBusy: false,
        error: null,
      },
      fields: [
        { key: 'kode', label: 'Kode' },
        { key: 'nama', label: 'Nama Sub Kegiatan' },
        { key: 'kegiatan', label: 'Kegiatan' },
        { key: 'satuan_kerja', label: 'OPD' },
        { key: 'tahun_kinerja', label: 'Tahun' },
        { key: 'anggaran', label: 'Anggaran', class: 'text-right' },
        { key: 'indikator_count', label: 'Indikator' },
        { key: 'kinerja_count', label: 'Dipakai Kinerja' },
        { key: 'action', label: 'Aksi', class: 'text-center' },
      ],
    }
  },

  computed: {
    computedFields() {
      if (this.$role.isPerangkatDaerah()) {
        return [
          { key: 'select', label: '', class: 'text-center', thStyle: { width: '40px' } },
          ...this.fields,
        ]
      }
      return this.fields
    },

    filteredData() {
      const keyword = this.filter.keyword.trim().toLowerCase()

      if (!keyword) {
        return this.data
      }

      return this.data.filter((item) => [
        item.kode,
        item.nama,
        item.kegiatan?.nama,
        item.kegiatan?.program?.nama,
        item.satuan_kerja?.satuan_kerja_nama,
        item.tahun_kinerja,
      ].some((value) => String(value || '').toLowerCase().includes(keyword)))
    },
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData() {
      try {
        this.isBusy.getData = true

        const { data } = await axios.get('sub-kegiatan-data', {
          params: {
            satuan_kerja_id: this.$role.isSuper() ? this.filter.satuan_kerja_id : null,
            tahun_kinerja: this.filter.tahun_kinerja,
          },
        })

        this.data = data
        this.selectedIds = []
        this.selectAll = false
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: error.response?.data?.message || 'Gagal mengambil data!',
        })
      } finally {
        this.isBusy.getData = false
      }
    },

    formatAnggaran(value) {
      return Number(value || 0).toLocaleString('id-ID')
    },

    totalKinerja(item) {
      return Number(item.kinerja_sub_kegiatan_count || 0) + Number(item.kinerja_sub_kegiatan_kab_kota_count || 0)
    },

    toggleSelectAll(checked) {
      if (checked) {
        this.selectedIds = this.filteredData.map(item => item.id)
      } else {
        this.selectedIds = []
      }
    },

    destroy(id) {
      doDestroy({
        preConfirm: async () => {
          try {
            await axios.delete(`sub-kegiatan-data/${id}`)
            this.getData()
            return true
          } catch (error) {
            Swal.showValidationMessage(
              error.response?.data?.message || 'Gagal menghapus data!'
            )
            return false
          }
        },
      })
    },

    async destroySelected() {
      if (this.selectedIds.length === 0) return

      const result = await Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Akan menghapus ${this.selectedIds.length} data sub kegiatan terpilih`,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#bd2130',
        confirmButtonText: 'Ya, Hapus Terpilih',
        cancelButtonText: 'Batal',
      })

      if (result.value) {
        try {
          const { data } = await axios.post('sub-kegiatan-data/destroy-batch', {
            ids: this.selectedIds,
          })
          Swal.fire({
            type: data.failed_count > 0 ? 'warning' : 'success',
            title: 'Proses Hapus Terpilih',
            text: data.message,
          })
          this.getData()
        } catch (error) {
          Swal.fire({
            type: 'error',
            title: 'Gagal Hapus Data!',
            text: error.response?.data?.message || 'Terjadi kesalahan server',
          })
        }
      }
    },

    async destroyAllData() {
      if (this.filteredData.length === 0) return

      const result = await Swal.fire({
        title: 'PERINGATAN HAPUS SEMUA DATA!',
        text: `Apakah Anda yakin ingin menghapus SELURUH (${this.filteredData.length}) data sub kegiatan untuk tahun ${this.filter.tahun_kinerja}?`,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#bd2130',
        confirmButtonText: 'Ya, Hapus Semua Data',
        cancelButtonText: 'Batal',
      })

      if (result.value) {
        try {
          const { data } = await axios.delete('sub-kegiatan-data/destroy-all', {
            data: {
              tahun_kinerja: this.filter.tahun_kinerja,
            },
          })
          Swal.fire({
            type: data.failed_count > 0 ? 'warning' : 'success',
            title: 'Proses Hapus Semua Data',
            text: data.message,
          })
          this.getData()
        } catch (error) {
          Swal.fire({
            type: 'error',
            title: 'Gagal Hapus Semua Data!',
            text: error.response?.data?.message || 'Terjadi kesalahan server',
          })
        }
      }
    },

    showImportModal() {
      this.importModal.file = null
      this.importModal.items = []
      this.importModal.error = null
      this.importModal.show = true
    },

    downloadTemplateCsv() {
      const templateContent = '\uFEFFKode Kegiatan;Kode Sub Kegiatan;Nama Sub Kegiatan;Anggaran\r\n1.01.02.2.01;1.01.02.2.01.0003;Pembangunan Ruang Guru/Kepala Sekolah/TU;500000000\r\n1.01.02.2.01;1.01.02.2.01.0004;Rehabilitasi Sedang/Berat Ruang Kelas Sekolah Dasar;350000000'
      const blob = new Blob([templateContent], { type: 'text/csv;charset=utf-8;' })
      const link = document.createElement('a')
      const url = URL.createObjectURL(blob)
      link.setAttribute('href', url)
      link.setAttribute('download', 'Template_Import_Master_Sub_Kegiatan.csv')
      link.style.visibility = 'hidden'
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
    },

    onFileChange(file) {
      if (!file) {
        this.importModal.items = []
        return
      }

      const reader = new FileReader()
      reader.onload = (e) => {
        const text = e.target.result
        this.parseCsvText(text)
      }
      reader.readAsText(file)
    },

    parseCsvText(text) {
      this.importModal.error = null
      const lines = text.split(/\r\n|\n/)
      const items = []

      for (let i = 0; i < lines.length; i++) {
        let line = lines[i].trim()
        if (!line) continue

        if (i === 0 && (line.toLowerCase().includes('kode') || line.toLowerCase().includes('nama'))) {
          continue
        }

        const delimiter = line.includes(';') ? ';' : ','
        const parts = line.split(delimiter).map(p => p.trim().replace(/^"|"$/g, ''))

        if (parts.length >= 3) {
          const kodeKegiatan = parts[0]
          const kode = parts[1]
          const nama = parts[2]
          let rawAnggaran = parts[3] || '0'

          rawAnggaran = rawAnggaran.replace(/rp/gi, '').replace(/\./g, '').replace(/,/g, '.').trim()
          const anggaran = parseFloat(rawAnggaran) || 0

          if (kodeKegiatan && kode && nama) {
            items.push({ kode_kegiatan: kodeKegiatan, kode, nama, anggaran })
          }
        }
      }

      if (items.length === 0) {
        this.importModal.error = 'Tidak ada baris data valid yang ditemukan pada file CSV.'
      }

      this.importModal.items = items
    },

    async submitImport() {
      if (this.importModal.items.length === 0) {
        Swal.fire({ type: 'warning', title: 'File belum dipilih atau data tidak valid!' })
        return
      }

      try {
        this.importModal.isBusy = true
        this.importModal.error = null

        const payload = {
          satuan_kerja_id: this.$role.isSuper() ? this.filter.satuan_kerja_id : null,
          tahun_kinerja: this.filter.tahun_kinerja,
          items: this.importModal.items,
        }

        const { data } = await axios.post('sub-kegiatan-data/import', payload)

        let htmlContent = `<div style="text-align: left; font-size: 14px;">`
        htmlContent += `<p class="mb-2"><strong>Hasil Proses Import:</strong></p>`
        htmlContent += `<ul class="mb-3">`
        htmlContent += `<li><strong class="text-success">${data.created_count || 0}</strong> Data Baru Dibuat</li>`
        htmlContent += `<li><strong class="text-info">${data.updated_count || 0}</strong> Data Diperbarui</li>`
        htmlContent += `<li><strong class="text-danger">${data.skipped_count || 0}</strong> Data Dilewati</li>`
        htmlContent += `</ul>`

        if (data.updated_items && data.updated_items.length > 0) {
          htmlContent += `<div class="mb-1"><strong class="text-info"><i class="fa fa-refresh mr-1"></i> Rincian Data Diperbarui (${data.updated_items.length}):</strong></div>`
          htmlContent += `<div style="max-height: 120px; overflow-y: auto; background: #eef7fc; padding: 8px; border-radius: 4px; border: 1px solid #b8dcee; font-size: 12px; margin-bottom: 12px;">`
          htmlContent += data.updated_items.map(item => `<div>• ${item}</div>`).join('')
          htmlContent += `</div>`
        }

        if (data.skipped_items && data.skipped_items.length > 0) {
          htmlContent += `<div class="mb-1"><strong class="text-danger"><i class="fa fa-exclamation-triangle mr-1"></i> Rincian Data Dilewati (${data.skipped_items.length}):</strong></div>`
          htmlContent += `<div style="max-height: 150px; overflow-y: auto; background: #fdf2f2; padding: 8px; border-radius: 4px; border: 1px solid #f8b4b4; font-size: 12px; margin-bottom: 12px; color: #721c24;">`
          htmlContent += data.skipped_items.map(item => `<div>• ${item}</div>`).join('')
          htmlContent += `</div>`
        }

        htmlContent += `</div>`

        Swal.fire({
          type: (data.skipped_count > 0 && data.created_count === 0 && data.updated_count === 0) ? 'warning' : 'success',
          title: 'Import Selesai',
          html: htmlContent,
          confirmButtonText: 'Tutup',
        })

        this.importModal.show = false
        this.getData()
      } catch (error) {
        this.importModal.error = error.response?.data?.message || 'Gagal memproses import data!'
      } finally {
        this.importModal.isBusy = false
      }
    },
  },

  watch: {
    'filter.satuan_kerja_id': function () {
      this.getData()
    },
    'filter.tahun_kinerja': function () {
      this.getData()
    },
    selectedIds: function (newVal) {
      if (newVal.length === this.filteredData.length && this.filteredData.length > 0) {
        this.selectAll = true
      } else {
        this.selectAll = false
      }
    },
  },
}
</script>

<template>
  <b-card>
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
      <h5 class="mb-2 mb-md-0">Master Sub Kegiatan</h5>
      <div>
        <b-button v-if="$role.isPerangkatDaerah() && selectedIds.length > 0" variant="danger" class="mr-2 mb-2 mb-md-0" @click="destroySelected">
          <i class="fa fa-trash mr-1" aria-hidden="true"></i>
          Hapus Terpilih ({{ selectedIds.length }})
        </b-button>
        <b-button v-if="$role.isPerangkatDaerah() && data.length > 0" variant="outline-danger" class="mr-2 mb-2 mb-md-0" @click="destroyAllData">
          <i class="fa fa-trash-o mr-1" aria-hidden="true"></i>
          Hapus Semua
        </b-button>
        <b-button variant="info" class="mr-2 mb-2 mb-md-0" @click="showImportModal">
          <i class="fa fa-upload mr-1" aria-hidden="true"></i>
          Import CSV
        </b-button>
        <nuxt-link to="/admin/sub-kegiatan/create" class="btn btn-primary mb-2 mb-md-0">
          <i class="fa fa-plus" aria-hidden="true"></i>
          Tambah Sub Kegiatan
        </nuxt-link>
      </div>
    </div>

    <b-row class="align-items-end mb-3">
      <b-col v-if="$role.isSuper()" cols="12" md="4">
        <OptionSatuanKerja
          id="filter-sub-kegiatan-satuan-kerja"
          v-model="filter.satuan_kerja_id"
          label-title="Filter OPD"
          :required="false"
          :group-props="{ 'label-cols': 12, 'label-cols-md': 12, class: 'mb-md-0' }"
        />
      </b-col>
      <b-col cols="12" md="2">
        <b-form-group label="Tahun" label-class="font-weight-bold" label-for="filter-tahun" class="mb-md-0">
          <b-form-input id="filter-tahun" v-model.number="filter.tahun_kinerja" type="number" min="1900" max="2100"></b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" :md="$role.isSuper() ? 4 : 7">
        <b-form-group label="Cari" label-class="font-weight-bold" label-for="keyword" class="mb-md-0">
          <b-form-input id="keyword" v-model="filter.keyword" placeholder="Cari kode, nama sub kegiatan, kegiatan, program, atau OPD"></b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" :md="$role.isSuper() ? 2 : 3" class="text-md-right">
        <b-badge variant="info" class="px-3 py-2">{{ filteredData.length }} Sub Kegiatan</b-badge>
      </b-col>
    </b-row>

    <b-table responsive hover striped :busy="isBusy.getData" :items="filteredData" :fields="computedFields" show-empty class="table-bordered" head-variant="info">
      <template #head(select)>
        <b-form-checkbox v-model="selectAll" @change="toggleSelectAll"></b-form-checkbox>
      </template>

      <template #cell(select)="row">
        <b-form-checkbox v-model="selectedIds" :value="row.item.id"></b-form-checkbox>
      </template>

      <template #cell(kode)="row">
        <span class="text-nowrap">{{ row.item.kode }}</span>
      </template>

      <template #cell(nama)="row">
        <strong>{{ row.item.nama }}</strong>
      </template>

      <template #cell(kegiatan)="row">
        {{ row.item.kegiatan?.nama || '-' }}
        <div v-if="row.item.kegiatan?.program?.nama" class="text-muted small">{{ row.item.kegiatan.program.nama }}</div>
      </template>

      <template #cell(satuan_kerja)="row">
        {{ row.item.satuan_kerja?.satuan_kerja_nama || '-' }}
        <div v-if="row.item.satuan_kerja_id" class="text-muted small">{{ row.item.satuan_kerja_id }}</div>
      </template>

      <template #cell(anggaran)="row">
        {{ formatAnggaran(row.item.anggaran) }}
      </template>

      <template #cell(indikator_count)="row">
        <b-badge :variant="row.item.indikator_count ? 'info' : 'secondary'" class="px-3 py-2">
          {{ row.item.indikator_count || 0 }}
        </b-badge>
      </template>

      <template #cell(kinerja_count)="row">
        <b-badge :variant="totalKinerja(row.item) ? 'warning' : 'secondary'" class="px-3 py-2">
          {{ totalKinerja(row.item) }}
        </b-badge>
      </template>

      <template #cell(action)="row">
        <div class="text-nowrap">
          <nuxt-link :to="`/admin/sub-kegiatan/${row.item.id}/edit`" class="btn btn-outline-warning btn-sm m-1 rounded-circle" title="Edit">
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </nuxt-link>
          <b-button @click="destroy(row.item.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
            <i class="fa fa-trash" aria-hidden="true"></i>
          </b-button>
        </div>
      </template>
    </b-table>

    <!-- Modal Import CSV -->
    <b-modal id="modal-import-sub-kegiatan" v-model="importModal.show" title="Import Master Sub Kegiatan CSV" size="lg" hide-footer>
      <div class="alert alert-info">
        <h6><i class="fa fa-info-circle mr-1"></i> Format File CSV:</h6>
        <p class="mb-1">File CSV membutuhkan 4 kolom: <strong>Kode Kegiatan</strong>, <strong>Kode Sub Kegiatan</strong>, <strong>Nama Sub Kegiatan</strong>, dan <strong>Anggaran</strong>.</p>
        <p class="mb-2 text-muted small">Data akan diimport ke Tahun Kinerja: <strong>{{ filter.tahun_kinerja }}</strong>.</p>
        <b-button variant="outline-primary" size="sm" @click="downloadTemplateCsv">
          <i class="fa fa-download mr-1"></i> Unduh Template CSV
        </b-button>
      </div>

      <b-form-group label="Pilih File CSV" label-class="font-weight-bold">
        <b-form-file v-model="importModal.file" accept=".csv,.txt" placeholder="Pilih atau drop file CSV di sini..." drop-placeholder="Drop file di sini..." @input="onFileChange"></b-form-file>
      </b-form-group>

      <b-alert v-if="importModal.error" variant="danger" show>{{ importModal.error }}</b-alert>

      <div v-if="importModal.items.length > 0" class="mt-3">
        <h6>Preview Data Ditemukan ({{ importModal.items.length }} data):</h6>
        <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
          <table class="table table-sm table-bordered table-striped">
            <thead class="thead-light">
              <tr>
                <th>No</th>
                <th>Kode Kegiatan</th>
                <th>Kode Sub Kegiatan</th>
                <th>Nama Sub Kegiatan</th>
                <th class="text-right">Anggaran</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, idx) in importModal.items" :key="idx">
                <td>{{ idx + 1 }}</td>
                <td><code>{{ item.kode_kegiatan }}</code></td>
                <td><code>{{ item.kode }}</code></td>
                <td>{{ item.nama }}</td>
                <td class="text-right">Rp {{ formatAnggaran(item.anggaran) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="d-flex justify-content-end mt-4">
        <b-button variant="secondary" class="mr-2" @click="importModal.show = false">Batal</b-button>
        <b-button variant="primary" :disabled="importModal.items.length === 0 || importModal.isBusy" @click="submitImport">
          <b-spinner v-if="importModal.isBusy" small class="mr-1"></b-spinner>
          <i v-else class="fa fa-check mr-1"></i> Proses Import
        </b-button>
      </div>
    </b-modal>
  </b-card>
</template>
