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
      },
      isBusy: {
        getData: false,
      },
      fields: [
        { key: 'peg_nip', label: 'NIP' },
        { key: 'peg_nama', label: 'Nama Pegawai' },
        { key: 'satuan_kerja', label: 'OPD' },
        { key: 'jabatan_nama', label: 'Jabatan' },
        { key: 'unit_kerja_nama', label: 'Unit Kerja' },
        { key: 'peg_status', label: 'Status' },
        { key: 'tim_kerja_count', label: 'Dipakai' },
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
        item.peg_nip,
        item.peg_nama,
        item.satuan_kerja?.satuan_kerja_nama,
        item.jabatan_nama,
        item.unit_kerja_nama,
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

        const { data } = await axios.get('pegawai-data', {
          params: {
            satuan_kerja_id: this.$role.isSuper() ? this.filter.satuan_kerja_id : null,
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

    statusLabel(status) {
      return String(status) === '1' ? 'Aktif' : 'Tidak Aktif'
    },

    statusVariant(status) {
      return String(status) === '1' ? 'success' : 'secondary'
    },

    destroy(id) {
      doDestroy({
        preConfirm: async () => {
          await axios.delete(`pegawai-data/${id}`)
          this.getData()
          return true
        }
      })
    },
  },

  watch: {
    'filter.satuan_kerja_id': function () {
      this.getData()
    },
  },
}
</script>

<template>
  <b-card>
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
      <h5 class="mb-2 mb-md-0">Master Pegawai</h5>
      <nuxt-link to="/admin/pegawai/create" class="btn btn-primary">
        <i class="fa fa-plus" aria-hidden="true"></i>
        Tambah Pegawai
      </nuxt-link>
    </div>

    <b-row class="align-items-end mb-3">
      <b-col v-if="$role.isSuper()" cols="12" md="5">
        <OptionSatuanKerja
          id="filter-pegawai-satuan-kerja"
          v-model="filter.satuan_kerja_id"
          label-title="Filter OPD"
          :required="false"
          :group-props="{ 'label-cols': 12, 'label-cols-md': 12, class: 'mb-md-0' }"
        />
      </b-col>
      <b-col cols="12" :md="$role.isSuper() ? 5 : 6">
        <b-form-group label="Cari" label-class="font-weight-bold" label-for="keyword" class="mb-md-0">
          <b-form-input id="keyword" v-model="filter.keyword" placeholder="Cari NIP, nama, jabatan, unit kerja, atau OPD"></b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" :md="$role.isSuper() ? 2 : 6" class="text-md-right">
        <b-badge variant="info" class="px-3 py-2">{{ filteredData.length }} Pegawai</b-badge>
      </b-col>
    </b-row>

    <b-table responsive hover striped :busy="isBusy.getData" :items="filteredData" :fields="fields" show-empty class="table-bordered" head-variant="info">
      <template #cell(peg_nip)="row">
        <span class="text-nowrap">{{ row.item.peg_nip }}</span>
      </template>

      <template #cell(peg_nama)="row">
        <strong>{{ row.item.peg_nama }}</strong>
      </template>

      <template #cell(satuan_kerja)="row">
        {{ row.item.satuan_kerja?.satuan_kerja_nama || '-' }}
        <div v-if="row.item.id_satuan_kerja" class="text-muted small">{{ row.item.id_satuan_kerja }}</div>
      </template>

      <template #cell(peg_status)="row">
        <b-badge :variant="statusVariant(row.item.peg_status)" class="px-3 py-2">
          {{ statusLabel(row.item.peg_status) }}
        </b-badge>
      </template>

      <template #cell(tim_kerja_count)="row">
        <b-badge :variant="row.item.tim_kerja_count ? 'warning' : 'secondary'" class="px-3 py-2">
          {{ row.item.tim_kerja_count || 0 }}
        </b-badge>
      </template>

      <template #cell(action)="row">
        <div class="text-nowrap">
          <nuxt-link :to="`/admin/pegawai/${row.item.id}/edit`" class="btn btn-outline-warning btn-sm m-1 rounded-circle" title="Edit">
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
