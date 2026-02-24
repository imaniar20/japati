<script>
import axios from 'axios'
import { destroy as doDestroy } from '~/plugins/swal'
import { mapGetters } from 'vuex'

export default {
  middleware: ['auth'],

  async asyncData({ query }) {
    const { data: {data, penyebabKegagalan} } = await axios.get('kinerja-langkah-aksi', {
      params: {
        penyebab_kegagalan_id: query.penyebab_kegagalan_id
      }
    })

    return {
      data,
      penyebabKegagalan,
    }
  },

  data() {
    return {
      table: {
        fields: [
          { key: 'no', label: 'No' },
          { key: 'sub_kegiatan', label: 'Sub Kegiatan' },
          { key: 'sasaran', label: 'Sasaran Langkah Aksi' },
          { key: 'langkah_aksi', label: 'Langkah Aksi' },
          { key: 'indikator', label: 'Indikator Langkah Aksi' },
          { key: 'satuan', label: 'Satuan' },
          { key: 'target_bulanan', label: 'Target Kinerja' },
          { key: 'realisasi_bulanan', label: 'Realisasi Kinerja' },
          { key: 'action', label: 'Aksi' },
        ]
      },
      filter: {
        satuan_kerja_id: null,
        sasaran: null,
        indikator: null,
        sub_kegiatan_id: null,
      },
      isBusy: {
        doFilter: false,
        exportExcel: false,
      },

    }
  },

  computed: {
    ...mapGetters({
      user: 'auth/user'
    }),
  },

  watch: {
    filter: {
      handler: function () {
        this.doFilter()
      },
      deep: true,
    }
  },

  mounted() {
    this.filter.satuan_kerja_id = this.user.satuan_kerja_id
  },

  created() {
    /** prepend field Satuan Kerja jika akun super atau setda */
    if (this.$role.isSuper() || this.$role.isSetda() || this.$role.isViewAll()) {
      this.table.fields.splice(1, 0, { key: 'satuan_kerja', label: 'Satuan Kerja' })
    }
  },

  methods: {
    destroy(index, id) {
      doDestroy({
        preConfirm: async () => {
          return axios.delete(`/kinerja-langkah-aksi/${id}`)
            .then(() => {
              this.data.data.splice(index, 1)

              return true
            })
        }
      })
    },
    async doFilter(page = 1) {
      this.isBusy.doFilter = true

      const { data: {data} } = await axios.get('kinerja-langkah-aksi', {
        params: {
          filter: this.filter,
          penyebab_kegagalan_id: this.penyebabKegagalan?.id,
          page,
        }
      })

      this.data = data
      this.isBusy.doFilter = false
    },
    clearFilter() {
      this.$router.push('/kinerja-langkah-aksi')
      this.penyebabKegagalan = null
      this.doFilter()
    },
    async exportExcel() {
      this.isBusy.exportExcel = true

      try {
        const { data } = await axios.get('/kinerja-langkah-aksi/export', {
          params: {
            filter: this.filter,
          },
          responseType: 'blob'
        })

        const url = window.URL.createObjectURL(new Blob([data]))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', 'Kinerja Langah Aksi.xlsx')
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
    <div class="text-right">
      <b-button variant="success" @click="exportExcel" :disabled="isBusy.exportExcel">
        <b-spinner small v-if="isBusy.exportExcel"></b-spinner>
        <i v-else class="ti-download" aria-hidden="true"></i>
        Export
      </b-button>
      <template v-if="$role.isSuper() || $role.isPerangkatDaerah()" >
        <nuxt-link to="/kinerja-langkah-aksi/create" class="btn btn-primary">
          <i class="ti-plus" aria-hidden="true"></i> Tambah
        </nuxt-link>
      </template>
    </div>

    <div>
      <b-row>
        <b-col sm="6" md="4">
          <FilterSatuanKerja v-if="$role.isSuper() || $role.isSetda() || $role.isViewAll()" v-model="filter.satuan_kerja_id" :is-setda="$role.isSetda()" />
          <FilterSasaranKinerja v-model="filter.sasaran" model="kinerja-langkah-aksi" />
        </b-col>
        <b-col sm="6" md="4">
          <FilterIndikatorKinerja v-model="filter.indikator" model="kinerja-langkah-aksi" />
          <FilterSubKegiatan v-model="filter.sub_kegiatan_id" :satker-id="filter.satuan_kerja_id" />
        </b-col>
      </b-row>
    </div>

    <b-alert v-if="Boolean(penyebabKegagalan)" show variant="warning">
      Data difilter dari Penyebab Kegagalan Triwulan {{ penyebabKegagalan.triwulan }} <b>{{ penyebabKegagalan.penyebab }}</b> <br>
      <b-button variant="danger" size="sm" class="mt-2" @click="clearFilter">Hapus Filter</b-button>
    </b-alert>

    <b-table responsive hover striped sticky-header="calc(100vh - 300px)" :fields="table.fields" :items="data.data" :busy="isBusy.doFilter" show-empty class="table-bordered" head-variant="info">
      <template #cell(no)="row">
        <div class="text-center">
          <b>{{ data.from + row.index }}</b> <br>
          <i v-if="row.item.kinerja_tidak_tercapai_count" class="fa-stack" v-b-tooltip.hover title="Penilaian PD" style="cursor:help">
            <i class="fa fa-circle fa-stack-2x text-white"></i>
            <i class="fa fa-times text-danger fa-stack-1x"></i>
          </i> <br>
          <i v-if="!row.item.capaian || row.item.capaian < 100" class="fa-stack" v-b-tooltip.hover title="Tidak tercapai" style="cursor:help">
            <i class="fa fa-circle fa-stack-2x text-white"></i>
            <i class="fa fa-times text-warning fa-stack-1x"></i>
          </i>
        </div>
      </template>
      <template v-if="$role.isSuper() || $role.isSetda() || $role.isViewAll()" #cell(satuan_kerja)="row">
        {{ row.value.satuan_kerja_nama }}
      </template>
      <template #cell(sub_kegiatan)="row">
        {{ row.value.nama || '-' }}
      </template>
      <template #cell(target_bulanan)="row">
        <div class="text-nowrap">
          <div v-for="(month, index) of $const.months" :key="index">
            <span class="text-capitalize">{{ month[0] }}</span>: <b>{{ row.value[month[0]] }}</b>
          </div>
        </div>
      </template>
      <template #cell(realisasi_bulanan)="row">
        <div class="text-nowrap">
          <div v-for="(month, index) of $const.months" :key="index">
            <span class="text-capitalize">{{ month[0] }}</span>: <b>{{ row.value[month[0]] || '-' }}</b>
          </div>
        </div>
      </template>
      <template #cell(action)="row">
        <div class="text-nowrap">
          <nuxt-link :to="`/kinerja-langkah-aksi/${row.item.id}`" class="btn btn-outline-success btn-sm m-1 rounded-circle" title="Detail">
            <i class="fa fa-eye" aria-hidden="true"></i>
          </nuxt-link>
          <nuxt-link v-if="$role.isSuper() || $role.isPerangkatDaerah()" :to="`/kinerja-langkah-aksi/${row.item.id}/edit`" class="btn btn-outline-warning btn-sm m-1 rounded-circle" title="Edit">
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </nuxt-link>
          <b-button v-if="$role.isSuper() || $role.isPerangkatDaerah()" @click="destroy(row.index, row.item.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
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