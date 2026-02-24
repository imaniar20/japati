<script>
import axios from 'axios'
import { destroy as doDestroy } from '~/plugins/swal'
import ValidasiSkp from '~/components/ValidasiSkp.vue'

export default {
  middleware: ['auth'],

  components: {
    ValidasiSkp,
  },

  async asyncData({ $role }) {
    const { data } = await axios.get('sasaran-strategis-pd')
    
    /**
      * khusus setda di sasaran strategis PD,
      * akun biro view-only sedangkan akun setda full access
     */
    const canCrud = $role.isSuper() || $role.isSetda() || ($role.isPerangkatDaerah() && !$role.isBiro())

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
          { key: 'sasaran_strategis', label: 'Sasaran Strategis RPJMD' },
          { key: 'indikator_sasaran_strategis', label: 'IKU Gubernur' },
          { key: 'sasaran_strategis_satker', label: 'Sasaran Strategis PD' },
          { key: 'iku', label: 'IKU PD' },
          { key: 'satuan', label: 'Satuan' },
          { key: 'target', label: 'Target Kinerja' },
          { key: 'realisasi', label: 'Realisasi' },
          { key: 'action', label: 'Aksi' },
          { key: 'keterangan', label: 'Seharusnya' },
        ]
      },
      filter: {
        satuan_kerja_id: null,
        sasaran_strategis_id: null,
        indikator_sasaran_strategis_id: null,
        status_validasi: null,
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
    if (this.$role.isSuper() || this.$role.isViewAll()) {
      this.table.fields.splice(1, 0, { key: 'satuan_kerja', label: 'Satuan Kerja' })
    }
  },

  methods: {
    destroy(index, id) {
      doDestroy({
        preConfirm: async () => {
          return axios.delete(`/sasaran-strategis-pd/${id}`)
            .then(() => {
              this.data.data.splice(index, 1)

              return true
            })
        }
      })
    },
    async doFilter(page = 1) {
      this.isBusy.doFilter = true

      const { data } = await axios.get('sasaran-strategis-pd', {
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
        const { data } = await axios.get('/sasaran-strategis-pd/export', {
          params: {
            filter: this.filter,
          },
          responseType: 'blob'
        })

        const url = window.URL.createObjectURL(new Blob([data]))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', 'Sasaran Strategis Perangkat Daerah.xlsx')
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
      <template v-if="canCrud">
        <nuxt-link to="/sasaran-strategis-pd/create" class="btn btn-primary">
          <i class="ti-plus" aria-hidden="true"></i> Tambah
        </nuxt-link>
      </template>
    </div>

    <div>
      <b-row>
        <b-col sm="6" md="4">
          <FilterSatuanKerja v-if="$role.isSuper() || $role.isViewAll()" v-model="filter.satuan_kerja_id" />
          <FilterStatusValidasi v-if="$role.isSuper() || $role.isViewAll()" v-model="filter.status_validasi" />
        </b-col>
        <b-col sm="6" md="4">
          <FilterSasaranStrategis v-model="filter.sasaran_strategis_id" />
        </b-col>
        <b-col sm="6" md="4">
          <FilterIndikatorSasaranStrategis v-model="filter.indikator_sasaran_strategis_id" />
        </b-col>
      </b-row>
    </div>

    <b-table responsive hover striped sticky-header="calc(100vh - 300px)" :fields="table.fields" :items="data.data" :busy="isBusy.doFilter" show-empty class="table-bordered mt-2" head-variant="info" >
      <template #cell(no)="row">
        <div class="text-center">
          <b>{{ data.from + row.index }}</b> <br>
          <i v-if="!row.item.tercapai" class="fa-stack" v-b-tooltip.hover title="Tidak tercapai" style="cursor:help">
            <i class="fa fa-circle fa-stack-2x text-white"></i>
            <i class="fa fa-times text-warning fa-stack-1x"></i>
          </i>
          <i v-if="row.item.kinerja_tidak_tercapai_count" class="fa-stack" v-b-tooltip.hover title="Penilaian PD" style="cursor:help">
            <i class="fa fa-circle fa-stack-2x text-white"></i>
            <i class="fa fa-times text-danger fa-stack-1x"></i>
          </i>
        </div>
      </template>
      <template v-if="$role.isSuper() || $role.isViewAll()" #cell(satuan_kerja)="row">
        {{ row.value.satuan_kerja_nama }}
      </template>
      <template #cell(sasaran_strategis)="row">
        {{ row.value.nomor }} - {{ row.value.sasaran }}
      </template>
      <template #cell(indikator_sasaran_strategis)="row">
        {{ row.value.nomor }} - {{ row.value.indikator }}
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
          <nuxt-link :to="`/sasaran-strategis-pd/${row.item.id}`" class="btn btn-outline-success btn-sm m-1 rounded-circle" title="Detail">
            <i class="fa fa-eye" aria-hidden="true"></i>
          </nuxt-link>
          <nuxt-link v-if="canCrud" :to="`/sasaran-strategis-pd/${row.item.id}/edit`" class="btn btn-outline-warning btn-sm m-1 rounded-circle" title="Edit">
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </nuxt-link>
          <b-button v-if="canCrud" @click="destroy(row.index, row.item.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
            <i class="fa fa-trash" aria-hidden="true"></i>
          </b-button>
          <template v-if="$role.isSuper()">
            <b-button @click="$refs.validasiSkp.validate('sasaran-strategis-pd', row.item.id, row.item.skp_exists)" :variant="row.item.skp_exists ? 'success' : 'outline-primary'" size="sm" class="m-1 rounded-circle"  :title="row.item.skp_exists ? 'Validasi Ulang' : 'Validasi'">
              <i class="fa fa-check" aria-hidden="true"></i>
            </b-button>
            <b-button v-if="!row.item.skp_exists" @click="$refs.validasiSkp.reject('sasaran-strategis-pd', row.item.id)" variant="danger" size="sm" class="m-1 rounded-circle"  title="Data tidak sesuai">
              <i class="fa fa-times" aria-hidden="true"></i>
            </b-button>
          </template>
          <nuxt-link v-if="$role.isPerangkatDaerah()" :to="`/sasaran-strategis-pd/${row.item.id}/kinerja-program-cross`" class="btn btn-outline-primary btn-sm m-1 rounded-circle" title="Kinerja Cross">
            <i class="fa fa-sitemap" aria-hidden="true"></i>
          </nuxt-link>
        </div>
      </template>
      <template #cell(keterangan)="row">
        <div v-if="!row.item.skp_exists" style="white-space: break-spaces;">{{ row.item.riwayat_skp_rejected_latest?.keterangan || '' }}</div>
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

    <ValidasiSkp ref="validasiSkp" @success="doFilter(data.current_page)" />
  </b-card>
</template>