<script>
import axios from 'axios'
import { destroy as doDestroy } from '~/plugins/swal'

export default {
  middleware: ['auth'],

  async asyncData({ $role }) {
    const { data } = await axios.get('kinerja-bayangan')

    /**
      * khusus setda di kinerja makro,
      * akun biro view-only sedangkan akun setda full access
     */
    const canCrud = $role.isSuper() || $role.isSetda() || ($role.isPemerintahDaerah() && !$role.isBiro())

    return {
      data,
      canCrud,
    }
  },
  
  data() {
    return {
      table: {
        fields: [
          { key: 'no', label: 'No' },
          { key: 'sasaran', label: 'Sasaran' },
          { key: 'indikator', label: 'Indikator' },
          { key: 'target', label: 'Target Kinerja' },
          { key: 'realisasi', label: 'Realisasi Kinerja' },
          { key: 'action', label: 'Aksi' },
        ]
      },
      filter: {
        satuan_kerja_id: null,
      },
      isBusy: {
        doFilter: false,
        exportExcel: false,
      },
    }
  },

  watch: {
    filter: {
      handler: function () {
        this.doFilter()
      },
      deep: true,
    }
  },

  created() {
    /** prepend field Satuan Kerja jika akun super */
    if (this.$role.isSuper()) {
      this.table.fields.splice(1, 0, { key: 'satuan_kerja', label: 'Satuan Kerja' })
    }
  },

  methods: {
    destroy(index, id) {
      doDestroy({
        preConfirm: async () => {
          return axios.delete(`/kinerja-bayangan/${id}`)
            .then(() => {
              this.data.data.splice(index, 1)

              return true
            })
        }
      })
    },
    async doFilter(page = 1) {
      this.isBusy.doFilter = true

      const { data } = await axios.get('kinerja-bayangan', {
        params: {
          filter: this.filter,
          page,
        }
      })

      this.data = data
      this.isBusy.doFilter = false
    },

    async exportExcel() {
      this.isBusy.exportExcel = true

      try {
        const { data } = await axios.get('/kinerja-bayangan/export', {
          params: {
            filter: this.filter,
          },
          responseType: 'blob'
        })

        const url = window.URL.createObjectURL(new Blob([data]))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', 'Kinerja Cross-Cutting.xlsx')
        document.body.appendChild(link)
        link.click()
      } catch (error) {
        throw error
      } finally {
        this.isBusy.exportExcel = false
      }
    }
  },
}
</script>

<template>
  <b-card>
    <div class="text-right mb-2">
      <b-button variant="success" @click="exportExcel" :disabled="isBusy.exportExcel">
        <b-spinner small v-if="isBusy.exportExcel"></b-spinner>
        <i v-else class="ti-download" aria-hidden="true"></i>
        Export
      </b-button>
      <template v-if="canCrud">
        <nuxt-link to="/kinerja-cross-cutting/create" class="btn btn-primary">
          <i class="ti-plus" aria-hidden="true"></i> Tambah
        </nuxt-link>
      </template>
    </div>
    <div>
      <b-row>
        <b-col sm="6">
          <FilterSatuanKerja v-if="$role.isSuper()" v-model="filter.satuan_kerja_id">
          </FilterSatuanKerja>
        </b-col>
        <b-col sm="6">
        </b-col>
      </b-row>
    </div>
    <b-table responsive hover striped sticky-header="calc(100vh - 300px)" :fields="table.fields" :items="data.data" :busy="isBusy.doFilter" show-empty class="table-bordered mt-2" head-variant="info" >
      <template #cell(no)="row">
        <div class="text-center">
          <b>{{ data.from + row.index }}</b> <br>
        </div>
      </template>
      <template v-if="$role.isSuper()" #cell(satuan_kerja)="row">
        {{ row.value.satuan_kerja_nama }}
      </template>
      <template #cell(target)="row">
        <div class="text-nowrap">
          {{ row.item.tahun_mulai }}: <b>{{ row.item.target_1 }}</b> <br>
          {{ row.item.tahun_mulai + 1 }}: <b>{{ row.item.target_2 }}</b> <br>
          {{ row.item.tahun_mulai + 2 }}: <b>{{ row.item.target_3 }}</b> <br>
          {{ row.item.tahun_mulai + 3 }}: <b>{{ row.item.target_4 }}</b> <br>
          {{ row.item.tahun_mulai + 4 }}: <b>{{ row.item.target_5 }}</b>
        </div>
      </template>
      <template #cell(realisasi)="row">
        <div class="text-nowrap">
          {{ row.item.tahun_mulai }}: <b>{{ row.item.realisasi_1 || '-' }}</b> <br>
          {{ row.item.tahun_mulai + 1 }}: <b>{{ row.item.realisasi_2 || '-' }}</b> <br>
          {{ row.item.tahun_mulai + 2 }}: <b>{{ row.item.realisasi_3 || '-' }}</b> <br>
          {{ row.item.tahun_mulai + 3 }}: <b>{{ row.item.realisasi_4 || '-' }}</b> <br>
          {{ row.item.tahun_mulai + 4 }}: <b>{{ row.item.realisasi_5 || '-' }}</b>
        </div>
      </template>
      <template #cell(action)="row">
        <div class="text-nowrap">
          <nuxt-link :to="`/kinerja-cross-cutting/${row.item.id}`" class="btn btn-outline-success btn-sm m-1 rounded-circle" title="Detail">
            <i class="fa fa-eye" aria-hidden="true"></i>
          </nuxt-link>
          <nuxt-link v-if="canCrud" :to="`/kinerja-cross-cutting/${row.item.id}/edit`" class="btn btn-outline-warning btn-sm m-1 rounded-circle" title="Edit">
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </nuxt-link>
          <b-button v-if="canCrud" @click="destroy(row.index, row.item.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
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
        @change="doFilter($event)"
      >
        <template #page="{ page, active }">
          <i class="fa fa-spinner fa-pulse fa-fw" v-if="isBusy.doFilter && active"></i>
          <template v-else>{{ page }}</template>
        </template>
      </b-pagination>
    </div>
  </b-card>
</template>