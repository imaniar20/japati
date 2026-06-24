<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { destroy as doDestroy } from '~/plugins/swal'

export default {
  middleware: ['auth', 'role-perangkat-daerah'],

  data() {
    return {
      data: [],
      filter: {
        keyword: '',
        satuan_kerja_id: null,
        tahun_kinerja: this.$helper.getTahunKinerja(),
      },
      isBusy: {
        getData: false,
      },
      fields: [
        { key: 'kode', label: 'Kode' },
        { key: 'nama', label: 'Nama Kegiatan' },
        { key: 'program', label: 'Program' },
        { key: 'satuan_kerja', label: 'OPD' },
        { key: 'tahun_kinerja', label: 'Tahun' },
        { key: 'anggaran', label: 'Anggaran', class: 'text-right' },
        { key: 'sub_kegiatan_count', label: 'Sub Kegiatan' },
        { key: 'kinerja_kegiatan_count', label: 'Dipakai Kinerja' },
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
        item.kode,
        item.nama,
        item.program?.nama,
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

        const { data } = await axios.get('kegiatan-data', {
          params: {
            satuan_kerja_id: this.$role.isSuper() ? this.filter.satuan_kerja_id : null,
            tahun_kinerja: this.filter.tahun_kinerja,
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

    formatAnggaran(value) {
      return Number(value || 0).toLocaleString('id-ID')
    },

    destroy(id) {
      doDestroy({
        preConfirm: async () => {
          await axios.delete(`kegiatan-data/${id}`)
          this.getData()
          return true
        },
      })
    },
  },

  watch: {
    'filter.satuan_kerja_id': function () {
      this.getData()
    },
    'filter.tahun_kinerja': function () {
      this.getData()
    },
  },
}
</script>

<template>
  <b-card>
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
      <h5 class="mb-2 mb-md-0">Master Kegiatan</h5>
      <nuxt-link to="/admin/kegiatan/create" class="btn btn-primary">
        <i class="fa fa-plus" aria-hidden="true"></i>
        Tambah Kegiatan
      </nuxt-link>
    </div>

    <b-row class="align-items-end mb-3">
      <b-col v-if="$role.isSuper()" cols="12" md="4">
        <OptionSatuanKerja
          id="filter-kegiatan-satuan-kerja"
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
          <b-form-input id="keyword" v-model="filter.keyword" placeholder="Cari kode, nama kegiatan, program, atau OPD"></b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" :md="$role.isSuper() ? 2 : 3" class="text-md-right">
        <b-badge variant="info" class="px-3 py-2">{{ filteredData.length }} Kegiatan</b-badge>
      </b-col>
    </b-row>

    <b-table responsive hover striped :busy="isBusy.getData" :items="filteredData" :fields="fields" show-empty class="table-bordered" head-variant="info">
      <template #cell(kode)="row">
        <span class="text-nowrap">{{ row.item.kode }}</span>
      </template>

      <template #cell(nama)="row">
        <strong>{{ row.item.nama }}</strong>
      </template>

      <template #cell(program)="row">
        {{ row.item.program?.nama || '-' }}
        <div v-if="row.item.program?.kode" class="text-muted small">{{ row.item.program.kode }}</div>
      </template>

      <template #cell(satuan_kerja)="row">
        {{ row.item.satuan_kerja?.satuan_kerja_nama || '-' }}
        <div v-if="row.item.satuan_kerja_id" class="text-muted small">{{ row.item.satuan_kerja_id }}</div>
      </template>

      <template #cell(anggaran)="row">
        {{ formatAnggaran(row.item.anggaran) }}
      </template>

      <template #cell(sub_kegiatan_count)="row">
        <b-badge :variant="row.item.sub_kegiatan_count ? 'warning' : 'secondary'" class="px-3 py-2">
          {{ row.item.sub_kegiatan_count || 0 }}
        </b-badge>
      </template>

      <template #cell(kinerja_kegiatan_count)="row">
        <b-badge :variant="row.item.kinerja_kegiatan_count ? 'warning' : 'secondary'" class="px-3 py-2">
          {{ row.item.kinerja_kegiatan_count || 0 }}
        </b-badge>
      </template>

      <template #cell(action)="row">
        <div class="text-nowrap">
          <nuxt-link :to="`/admin/kegiatan/${row.item.id}/edit`" class="btn btn-outline-warning btn-sm m-1 rounded-circle" title="Edit">
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </nuxt-link>
          <b-button @click="destroy(row.item.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
            <i class="fa fa-trash" aria-hidden="true"></i>
          </b-button>
        </div>
      </template>
    </b-table>
  </b-card>
</template>
