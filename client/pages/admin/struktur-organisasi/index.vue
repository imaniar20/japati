<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  middleware: ['auth', 'role-super'],

  data() {
    return {
      data: [],
      filter: {
        keyword: '',
        satuan_kerja_id: null,
        level: null,
        withInactive: false,
      },
      isBusy: {
        getData: false,
      },
      fields: [
        { key: 'id', label: 'ID' },
        { key: 'satuan_kerja_nama', label: 'OPD' },
        { key: 'unit_kerja_nama', label: 'Unit Kerja' },
        { key: 'jabatan_nama', label: 'Jabatan' },
        { key: 'level', label: 'Level', class: 'text-center' },
        { key: 'status', label: 'Status', class: 'text-center' },
        { key: 'unit_kerja_aktif_selesai', label: 'Aktif Selesai' },
      ],
    }
  },

  computed: {
    satuanKerjaOptions() {
      const seen = new Set()
      const options = [{ value: null, text: 'Semua OPD' }]

      this.data.forEach((item) => {
        if (!item.satuan_kerja_id || seen.has(item.satuan_kerja_id)) {
          return
        }

        seen.add(item.satuan_kerja_id)
        options.push({
          value: item.satuan_kerja_id,
          text: item.satuan_kerja_nama || item.satuan_kerja_id,
        })
      })

      return options
    },
    levelOptions() {
      const levels = [...new Set(this.data.map((item) => item.level).filter((level) => level !== null && level !== undefined))]
        .sort((a, b) => Number(a) - Number(b))

      return [
        { value: null, text: 'Semua Level' },
        ...levels.map((level) => ({ value: level, text: `Level ${level}` })),
      ]
    },
    filteredData() {
      const keyword = this.filter.keyword.trim().toLowerCase()
      const satuanKerjaId = this.filter.satuan_kerja_id ? String(this.filter.satuan_kerja_id) : null
      const level = this.filter.level !== null && this.filter.level !== '' ? String(this.filter.level) : null

      return this.data.filter((item) => {
        if (satuanKerjaId && String(item.satuan_kerja_id) !== satuanKerjaId) {
          return false
        }

        if (level && String(item.level) !== level) {
          return false
        }

        if (!keyword) {
          return true
        }

        return [
          item.id,
          item.satuan_kerja_id,
          item.satuan_kerja_nama,
          item.unit_kerja_nama,
          item.jabatan_id,
          item.jabatan_nama,
        ].some((value) => String(value || '').toLowerCase().includes(keyword))
      })
    },
  },

  watch: {
    'filter.withInactive'() {
      this.getData()
    },
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData() {
      try {
        this.isBusy.getData = true

        const { data } = await axios.get('admin/struktur-organisasi', {
          params: {
            with_inactive: this.filter.withInactive ? 1 : 0,
          },
        })

        this.data = data
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: error.response?.data?.message || 'Gagal mengambil data!',
        })
      } finally {
        this.isBusy.getData = false
      }
    },
    statusText(item) {
      if (item.status === null || item.status === undefined) {
        return '-'
      }

      return Number(item.status) === 1 && !item.unit_kerja_aktif_selesai ? 'Aktif' : 'Nonaktif'
    },
    statusVariant(item) {
      return Number(item.status) === 1 && !item.unit_kerja_aktif_selesai ? 'success' : 'secondary'
    },
    formatDate(value) {
      return value ? String(value).substring(0, 10) : '-'
    },
  },
}
</script>

<template>
  <b-card>
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
      <h5 class="mb-2 mb-md-0">Master Unit Kerja / Struktur Organisasi</h5>
      <b-badge variant="info" class="px-3 py-2">{{ filteredData.length }} Data</b-badge>
    </div>

    <b-row class="align-items-end mb-3">
      <b-col cols="12" md="4">
        <b-form-group label="Cari" label-class="font-weight-bold" label-for="keyword" class="mb-md-0">
          <b-form-input id="keyword" v-model="filter.keyword"></b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="3">
        <b-form-group label="OPD" label-class="font-weight-bold" label-for="satuan_kerja_id" class="mb-md-0">
          <b-form-select id="satuan_kerja_id" v-model="filter.satuan_kerja_id" :options="satuanKerjaOptions"></b-form-select>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="2">
        <b-form-group label="Level" label-class="font-weight-bold" label-for="level" class="mb-md-0">
          <b-form-select id="level" v-model="filter.level" :options="levelOptions"></b-form-select>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="3" class="text-md-right">
        <b-form-checkbox v-model="filter.withInactive" switch>
          Tampilkan nonaktif
        </b-form-checkbox>
      </b-col>
    </b-row>

    <b-table responsive hover striped :busy="isBusy.getData" :items="filteredData" :fields="fields" show-empty class="table-bordered" head-variant="info">
      <template #cell(id)="row">
        <span class="text-nowrap">{{ row.item.id }}</span>
      </template>

      <template #cell(satuan_kerja_nama)="row">
        <strong>{{ row.item.satuan_kerja_nama || '-' }}</strong>
        <div v-if="row.item.satuan_kerja_id" class="text-muted small">{{ row.item.satuan_kerja_id }}</div>
      </template>

      <template #cell(unit_kerja_nama)="row">
        <strong>{{ row.item.unit_kerja_nama || '-' }}</strong>
        <div v-if="row.item.lv2_unit_kerja_nama && row.item.lv2_unit_kerja_nama !== row.item.unit_kerja_nama" class="text-muted small">
          {{ row.item.lv2_unit_kerja_nama }}
        </div>
      </template>

      <template #cell(jabatan_nama)="row">
        {{ row.item.jabatan_nama || '-' }}
        <div v-if="row.item.jabatan_id" class="text-muted small">{{ row.item.jabatan_id }}</div>
      </template>

      <template #cell(level)="row">
        <b-badge variant="light" class="px-2 py-1">Level {{ row.item.level || '-' }}</b-badge>
      </template>

      <template #cell(status)="row">
        <b-badge :variant="statusVariant(row.item)" class="px-2 py-1">{{ statusText(row.item) }}</b-badge>
      </template>

      <template #cell(unit_kerja_aktif_selesai)="row">
        {{ formatDate(row.item.unit_kerja_aktif_selesai) }}
      </template>
    </b-table>
  </b-card>
</template>
