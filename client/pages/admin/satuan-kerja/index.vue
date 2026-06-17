<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { destroy as doDestroy } from '~/plugins/swal'

export default {
  middleware: ['auth', 'role-super'],

  data() {
    return {
      data: [],
      filter: {
        keyword: '',
      },
      isBusy: {
        getData: false,
      },
      fields: [
        { key: 'satuan_kerja_id', label: 'ID' },
        { key: 'satuan_kerja_nama', label: 'Nama OPD' },
        { key: 'satuan_kerja_nama_alias', label: 'Alias' },
        { key: 'kode', label: 'Kode' },
        { key: 'kode_skpd', label: 'Kode SKPD' },
        { key: 'tahun_id', label: 'Tahun' },
        { key: 'status', label: 'Status' },
        { key: 'action', label: 'Aksi', class: 'text-center' },
      ],
    }
  },

  computed: {
    filteredData() {
      const keyword = this.filter.keyword.trim().toLowerCase()

      if (!keyword) {
        return this.data
      }

      return this.data.filter((item) => [
        item.satuan_kerja_id,
        item.satuan_kerja_nama,
        item.satuan_kerja_nama_alias,
        item.kode,
        item.kode_skpd,
        item.satuan_kerja_alamat,
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

        const { data } = await axios.get('admin/satuan-kerja')

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
    statusLabel(status) {
      const value = Number(status)

      if (value === 1) {
        return 'Aktif'
      }

      if (value === 0) {
        return 'Tidak Aktif'
      }

      return '-'
    },
    statusVariant(status) {
      const value = Number(status)

      if (value === 1) {
        return 'success'
      }

      if (value === 0) {
        return 'secondary'
      }

      return 'light'
    },
    destroy(id) {
      doDestroy({
        preConfirm: async () => {
          await axios.delete(`admin/satuan-kerja/${id}`)
          this.getData()
          return true
        }
      })
    },
  },
}
</script>

<template>
  <b-card>
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
      <h5 class="mb-2 mb-md-0">Master Satuan Kerja / OPD</h5>
      <nuxt-link to="/admin/satuan-kerja/create" class="btn btn-primary">
        <i class="fa fa-plus" aria-hidden="true"></i>
        Tambah OPD
      </nuxt-link>
    </div>

    <b-row class="align-items-end mb-3">
      <b-col cols="12" md="6">
        <b-form-group label="Cari" label-class="font-weight-bold" label-for="keyword" class="mb-md-0">
          <b-form-input id="keyword" v-model="filter.keyword" placeholder="Cari nama OPD, alias, kode, atau alamat"></b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="6" class="text-md-right">
        <b-badge variant="info" class="px-3 py-2">{{ filteredData.length }} OPD</b-badge>
      </b-col>
    </b-row>

    <b-table responsive hover striped :busy="isBusy.getData" :items="filteredData" :fields="fields" show-empty class="table-bordered" head-variant="info">
      <template #cell(satuan_kerja_nama)="row">
        <strong>{{ row.item.satuan_kerja_nama }}</strong>
        <div v-if="row.item.satuan_kerja_alamat" class="text-muted small">{{ row.item.satuan_kerja_alamat }}</div>
      </template>

      <template #cell(status)="row">
        <b-badge :variant="statusVariant(row.item.status)" class="px-3 py-2">
          {{ statusLabel(row.item.status) }}
        </b-badge>
      </template>

      <template #cell(action)="row">
        <div class="text-nowrap">
          <nuxt-link :to="`/admin/satuan-kerja/${row.item.satuan_kerja_id}/edit`" class="btn btn-outline-warning btn-sm m-1 rounded-circle" title="Edit">
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </nuxt-link>
          <b-button @click="destroy(row.item.satuan_kerja_id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
            <i class="fa fa-trash" aria-hidden="true"></i>
          </b-button>
        </div>
      </template>
    </b-table>
  </b-card>
</template>
