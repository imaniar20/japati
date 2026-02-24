<script>
import axios from 'axios'
import { destroy as doDestroy } from '~/plugins/swal'

export default {
  middleware: ['auth'],
  data() {
    return {
      isBusy: false,
      data: {
        data: []
      },
      filter: {
        satuan_kerja_id: null,
        sasaran_strategis_id: null,
        indikator_sasaran_strategis_id: null,
      },
    }
  },

  watch: {
    filter: {
      handler: function () {
        this.getData()
      },
      deep: true,
    }
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData(page = 1) {
      this.isBusy = true

      const { data } = await axios.get('/lkip/narasi-pd', {
        params: {
          page,
          filter: this.filter,
        }
      })

      this.data = data
      this.isBusy = false
    },
    destroy(index, id) {
      doDestroy({
        preConfirm: async () => {
          return axios.delete(`/lkip/narasi-pd/${id}`)
            .then(() => {
              this.data.data.splice(index, 1)

              return true
            })
        }
      })
    }
  }
}
</script>

<template>
  <b-card>
    <div class="text-right">
      <nuxt-link v-if="$role.isPerangkatDaerah()" to="/lkip/narasi-pd/create" class="btn btn-primary">
        <i class="ti-plus" aria-hidden="true"></i> Tambah
      </nuxt-link>
    </div>

    <b-row>
      <b-col sm="6" md="4" v-if="$role.isSuper()">
        <FilterSatuanKerja  v-model="filter.satuan_kerja_id" />
      </b-col>
      <b-col sm="6" md="4">
        <FilterSasaranStrategis v-model="filter.sasaran_strategis_id" />
      </b-col>
      <b-col sm="6" md="4">
        <FilterIndikatorSasaranStrategis v-model="filter.indikator_sasaran_strategis_id" />
      </b-col>
    </b-row>

    <b-table-simple class="mt-3" :aria-busy="isBusy" responsive bordered striped hover>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-th>No</b-th>
        <b-th v-if="$role.isSuper()">Satuan Kerja</b-th>
        <b-th>Sasaran Strategis Gubernur</b-th>
        <b-th>IKU Gubernur</b-th>
        <b-th>Sasaran PD</b-th>
        <b-th>IKU PD</b-th>
        <b-th>Narasi</b-th>
        <b-th>Aksi</b-th>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="isBusy && !data.data.length">
          <b-td :colspan="$role.isSuper() ? 8 : 7"><b-spinner small></b-spinner> Sedang memuat data...</b-td>
        </b-tr>
        <b-tr class="text-center" v-else-if="!data.data.length">
          <b-td :colspan="$role.isSuper() ? 8 : 7">There are no records to show</b-td>
        </b-tr>
        <b-tr v-else v-for="(item, index) of data.data" :key="item.id">
          <b-td>{{ data.from + index }}</b-td>
          <b-td v-if="$role.isSuper()">{{ item.satuan_kerja.satuan_kerja_nama }}</b-td>
          <b-td>{{ item.sasaran_strategis.sasaran }}</b-td>
          <b-td>{{ item.indikator_sasaran_strategis.indikator }}</b-td>
          <b-td>{{ item.sasaran_strategis_pd }}</b-td>
          <b-td>{{ item.iku_pd }}</b-td>
          <b-td style="min-width:300px;">{{ `${item.narasi_1 || ''} ${item.narasi_2 || ''} ${item.narasi_3 || ''} ${item.narasi_4 || ''} ${item.narasi_5 || ''} ${item.narasi_6 || ''} ${item.narasi_7 || ''}` }}</b-td>
          <b-td>
            <div class="text-nowrap">
              <nuxt-link :to="`/lkip/narasi-pd/${item.id}`" class="btn btn-outline-success btn-sm m-1 rounded-circle" title="Detail">
                <i class="fa fa-eye" aria-hidden="true"></i>
              </nuxt-link>
              <nuxt-link v-if="$role.isPerangkatDaerah()" :to="`/lkip/narasi-pd/${item.id}/edit`" class="btn btn-outline-warning btn-sm m-1 rounded-circle" title="Edit">
                <i class="fa fa-pencil" aria-hidden="true"></i>
              </nuxt-link>
              <b-button v-if="$role.isPerangkatDaerah()" @click="destroy(index, item.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
                <i class="fa fa-trash" aria-hidden="true"></i>
              </b-button>
            </div>
          </b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>

    <div>
      <b-pagination
        v-model="data.current_page"
        :total-rows="data.total"
        :per-page="data.per_page"
        @change="getData($event)"
      >
        <template #page="{ page, active }">
          <i class="fa fa-spinner fa-pulse fa-fw" v-if="isBusy && active"></i>
          <template v-else>{{ page }}</template>
        </template>
      </b-pagination>
    </div>
  </b-card>
</template>
