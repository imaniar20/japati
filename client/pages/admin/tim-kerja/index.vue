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
        { key: 'nama', label: 'Nama Tim Kerja' },
        { key: 'satuan_kerja', label: 'OPD' },
        { key: 'unit_kerja', label: 'Unit Kerja Koordinasi' },
        { key: 'pengampu', label: 'Pengampu Outcome' },
        { key: 'usage', label: 'Dipakai' },
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
        item.nama,
        item.satuan_kerja?.satuan_kerja_nama,
        item.struktur_organisasi?.unit_kerja_nama,
        item.ketua?.peg_nip,
        item.ketua?.peg_nama,
        item.ketua?.jabatan_nama,
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

        const { data } = await axios.get('tim-kerja', {
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

    usageText(item) {
      const usage = item.usage_counts || {}
      const parts = []

      if (usage.kinerja_program) {
        parts.push(`${usage.kinerja_program} program`)
      }

      if (usage.kinerja_kegiatan) {
        parts.push(`${usage.kinerja_kegiatan} kegiatan`)
      }

      if (usage.kinerja_sub_kegiatan) {
        parts.push(`${usage.kinerja_sub_kegiatan} sub kegiatan`)
      }

      if (usage.kinerja_sub_kegiatan_kab_kota) {
        parts.push(`${usage.kinerja_sub_kegiatan_kab_kota} kab/kota`)
      }

      if (usage.skp) {
        parts.push(`${usage.skp} SKP`)
      }

      return parts.length ? parts.join(', ') : '-'
    },

    destroy(id) {
      doDestroy({
        preConfirm: async () => {
          await axios.delete(`tim-kerja/${id}`)
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
      <h5 class="mb-2 mb-md-0">Master Tim Kerja / Pengampu Outcome</h5>
      <nuxt-link to="/admin/tim-kerja/create" class="btn btn-primary">
        <i class="fa fa-plus" aria-hidden="true"></i>
        Tambah Tim Kerja
      </nuxt-link>
    </div>

    <b-row class="align-items-end mb-3">
      <b-col v-if="$role.isSuper()" cols="12" md="5">
        <OptionSatuanKerja
          id="filter-satuan-kerja"
          v-model="filter.satuan_kerja_id"
          label-title="Filter OPD"
          :required="false"
          :group-props="{ 'label-cols': 12, 'label-cols-md': 12, class: 'mb-md-0' }"
        />
      </b-col>
      <b-col cols="12" :md="$role.isSuper() ? 5 : 6">
        <b-form-group label="Cari" label-class="font-weight-bold" label-for="keyword" class="mb-md-0">
          <b-form-input id="keyword" v-model="filter.keyword" placeholder="Cari nama tim, OPD, unit kerja, atau pengampu"></b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" :md="$role.isSuper() ? 2 : 6" class="text-md-right">
        <b-badge variant="info" class="px-3 py-2">{{ filteredData.length }} Tim Kerja</b-badge>
      </b-col>
    </b-row>

    <b-table responsive hover striped :busy="isBusy.getData" :items="filteredData" :fields="fields" show-empty class="table-bordered" head-variant="info">
      <template #cell(nama)="row">
        <strong>{{ row.item.nama }}</strong>
      </template>

      <template #cell(satuan_kerja)="row">
        {{ row.item.satuan_kerja?.satuan_kerja_nama || '-' }}
      </template>

      <template #cell(unit_kerja)="row">
        <div>{{ row.item.struktur_organisasi?.unit_kerja_nama || '-' }}</div>
        <div v-if="row.item.struktur_organisasi?.jabatan_nama" class="text-muted small">
          {{ row.item.struktur_organisasi.jabatan_nama }}
        </div>
      </template>

      <template #cell(pengampu)="row">
        <strong>{{ row.item.ketua?.peg_nama || '-' }}</strong>
        <div v-if="row.item.ketua?.peg_nip" class="text-muted small">{{ row.item.ketua.peg_nip }}</div>
        <div v-if="row.item.ketua?.jabatan_nama" class="text-muted small">{{ row.item.ketua.jabatan_nama }}</div>
      </template>

      <template #cell(usage)="row">
        <b-badge :variant="row.item.usage_counts?.total ? 'warning' : 'secondary'" class="px-3 py-2">
          {{ row.item.usage_counts?.total || 0 }}
        </b-badge>
        <div class="small text-muted mt-1">{{ usageText(row.item) }}</div>
      </template>

      <template #cell(action)="row">
        <div class="text-nowrap">
          <nuxt-link :to="`/admin/tim-kerja/${row.item.id}/edit`" class="btn btn-outline-warning btn-sm m-1 rounded-circle" title="Edit">
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
