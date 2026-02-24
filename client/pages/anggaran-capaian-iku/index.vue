<script>
import axios from 'axios'
import { destroy as doDestroy } from '~/plugins/swal'

export default {
  middleware: ['role-super'],

  data() {
    return {
      data: {
        data: [],
        total: 0,
        per_page: 15,
        current_page: 1,
      },
      table: {
        fields: [
          { key: 'tahun_kinerja', label: 'Tahun' },
          { key: 'terpakai', label: 'Anggaran' },
          { key: 'tidak_terpakai', label: 'Realisasi' },
          { key: 'efisiensi', label: 'Efisiensi' },
          { key: 'action', label: 'Aksi' },
        ]
      },
      isBusy: {
        getData: false,
      },
    }
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData(page = 1) {
      this.isBusy.getData = true

      const { data } = await axios.get('anggaran-capaian-iku', {
        params: {
          page,
        }
      })

      this.data = data
      this.isBusy.getData = false
    },
    destroy(id) {
      doDestroy({
        preConfirm: async () => {
          await axios.delete(`/anggaran-capaian-iku/${id}`)
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
    <div class="text-right">
      <nuxt-link to="/anggaran-capaian-iku/create" class="btn btn-primary">
          <i class="ti-plus" aria-hidden="true"></i> Tambah
        </nuxt-link>
    </div>
    
    <b-table responsive hover striped sticky-header="calc(100vh - 300px)" :fields="table.fields" :items="data.data" :busy="isBusy.getData" show-empty class="table-bordered mt-2" head-variant="info">
      <template #cell(terpakai)="row">
        <span>{{ row.value | rupiah }}</span>
      </template>

      <template #cell(tidak_terpakai)="row">
        <span>{{ row.value | rupiah }}</span>
      </template>

      <template #cell(action)="row">
        <div class="text-nowrap">
          <nuxt-link :to="`/anggaran-capaian-iku/${row.item.id}/edit`" class="btn btn-outline-warning btn-sm m-1 rounded-circle" title="Edit">
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </nuxt-link>
          <b-button @click="destroy(row.item.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
            <i class="fa fa-trash" aria-hidden="true"></i>
          </b-button>
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