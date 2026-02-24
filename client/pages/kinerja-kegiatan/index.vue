<script>
import axios from 'axios'
import { destroy as doDestroy } from '~/plugins/swal'
import ValidasiSkp from '~/components/ValidasiSkp.vue'
import { mapGetters } from 'vuex'

export default {
  middleware: ['auth'],

  components: {
    ValidasiSkp,
  },

  async asyncData() {
    const { data } = await axios.get('kinerja-kegiatan')

    return {
      data,
    }
  },

  data() {
    return {
      table: {
        fields: [
          { key: 'no', label: 'No' },
          { key: 'program', label: 'Program' },
          { key: 'sasaran', label: 'Sasaran Kegiatan' },
          { key: 'kegiatan', label: 'Kegiatan' },
          { key: 'indikator', label: 'Indikator Kegiatan' },
          { key: 'satuan', label: 'Satuan' },
          { key: 'target_bulanan', label: 'Target Kinerja' },
          { key: 'realisasi_bulanan', label: 'Realisasi Kinerja' },
          { key: 'pengampu', label: 'Pengampu' },
          { key: 'action', label: 'Aksi' },
          { key: 'keterangan', label: 'Seharusnya' },
        ]
      },
      filter: {
        satuan_kerja_id: null,
        sasaran: null,
        indikator: null,
        status_validasi: null,
        pengampu: null,
        tim_kerja_id: null,
        v_struktur_organisasi_id: null,
      },
      isBusy: {
        doFilter: false,
        exportExcel: false,
      },
    }
  },

  computed: {
    ...mapGetters({
      user: 'auth/user',
      canEditValidasiPerencanaan: 'validasi-perencanaan/canEdit'
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

  created() {
    /** prepend field Satuan Kerja jika akun super atau setda */
    if (this.$role.isSuper() || this.$role.isSetda() || this.$role.isViewAll()) {
      this.table.fields.splice(1, 0, { key: 'satuan_kerja', label: 'Satuan Kerja' })
    } else {
      this.filter.satuan_kerja_id = this.user.satuan_kerja_id
    }
  },

  methods: {
    destroy(index, id) {
      doDestroy({
        preConfirm: async () => {
          return axios.delete(`/kinerja-kegiatan/${id}`)
            .then(() => {
              this.data.data.splice(index, 1)

              return true
            })
        }
      })
    },
    async doFilter(page = 1) {
      this.isBusy.doFilter = true

      const { data } = await axios.get('kinerja-kegiatan', {
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
        const { data } = await axios.get('/kinerja-kegiatan/export', {
          params: {
            filter: this.filter,
          },
          responseType: 'blob'
        })

        const url = window.URL.createObjectURL(new Blob([data]))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', 'Kinerja Kegiatan.xlsx')
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
        <nuxt-link to="/kinerja-kegiatan/create" class="btn btn-primary">
          <i class="ti-plus" aria-hidden="true"></i> Tambah
        </nuxt-link>
      </template>
    </div>

    <div>
      <b-row>
        <b-col sm="6" md="4">
          <FilterSatuanKerja v-if="$role.isSuper() || $role.isSetda() || $role.isViewAll()" v-model="filter.satuan_kerja_id" :is-setda="$role.isSetda()" />
          <FilterSasaranKinerja v-model="filter.sasaran" model="kinerja-kegiatan" />
        </b-col>
        <b-col sm="6" md="4">
          <FilterIndikatorKinerja v-model="filter.indikator" model="kinerja-kegiatan" />
          <FilterStatusValidasi v-if="$role.isSuper()" v-model="filter.status_validasi" />
        </b-col>
        <b-col sm="6" md="4">
          <FilterPengampu v-model="filter.pengampu" />
          <FilterPengampuTimKerja v-if="filter.pengampu == 'tim-kerja' && filter.satuan_kerja_id" v-model="filter.tim_kerja_id" :satuan-kerja-id="filter.satuan_kerja_id" />
          <FilterPengampuUnitKerja v-if="filter.pengampu == 'unit-kerja' && filter.satuan_kerja_id" v-model="filter.v_struktur_organisasi_id" :satuan-kerja-id="filter.satuan_kerja_id" type="kinerja-kegiatan" />
        </b-col>
      </b-row>
    </div>

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
      <template #cell(program)="row">
        {{ row.value.nama || '-' }}
      </template>
      <template #cell(kegiatan)="row">
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
      <template #cell(pengampu)="row">
        <span v-if="row.value == 'unit-kerja'">{{ row.item?.struktur_organisasi?.jabatan_nama }}</span>
        <span v-else-if="row.value == 'tim-kerja'">{{ row.item?.tim_kerja?.nama }} - {{ row.item?.tim_kerja?.ketua?.peg_nama }}</span>
        <span v-else>-</span>
      </template>
      <template #cell(action)="row">
        <div class="text-nowrap">
          <nuxt-link :to="`/kinerja-kegiatan/${row.item.id}`" class="btn btn-outline-success btn-sm m-1 rounded-circle" title="Detail">
            <i class="fa fa-eye" aria-hidden="true"></i>
          </nuxt-link>
          <nuxt-link v-if="($role.isSuper() || $role.isPerangkatDaerah()) && canEditValidasiPerencanaan" :to="`/kinerja-kegiatan/${row.item.id}/edit`" class="btn btn-outline-warning btn-sm m-1 rounded-circle" title="Edit">
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </nuxt-link>
          <b-button v-if="($role.isSuper() || $role.isPerangkatDaerah()) && canEditValidasiPerencanaan" @click="destroy(row.index, row.item.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
            <i class="fa fa-trash" aria-hidden="true"></i>
          </b-button>
          <template v-if="$role.isSuper()">
            <b-button @click="$refs.validasiSkp.validate('kinerja-kegiatan', row.item.id, row.item.skp_exists)" :variant="row.item.skp_exists ? 'success' : 'outline-primary'" size="sm" class="m-1 rounded-circle"  :title="row.item.skp_exists ? 'Validasi Ulang' : 'Validasi'">
              <i class="fa fa-check" aria-hidden="true"></i>
            </b-button>
            <b-button v-if="!row.item.skp_exists" @click="$refs.validasiSkp.reject('kinerja-kegiatan', row.item.id)" variant="danger" size="sm" class="m-1 rounded-circle"  title="Data tidak sesuai">
              <i class="fa fa-times" aria-hidden="true"></i>
            </b-button>
          </template>
          <nuxt-link v-if="$role.isPerangkatDaerah() && canEditValidasiPerencanaan" :to="`/kinerja-kegiatan/${row.item.id}/kinerja-sub-kegiatan-cross`" class="btn btn-outline-primary btn-sm m-1 rounded-circle" title="Kinerja Cross">
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