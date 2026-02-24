<script>
import axios from 'axios';
import { debounce } from 'lodash'

export default {
  middleware: [
    'auth',
    'role-pemerintah-daerah',
  ],
  data() {
    const satker = this.$store.getters['auth/user'].satuan_kerja_id
    const id = this.$route.params.id

    return {
      id,
      data: {
        data: [],
        from: 1,
        current_page: 1,
        total: 0,
        per_page: 20,
      },
      table: {
        fields: [
          { key: 'sasaran_strategis_satker', label: 'Sasaran Strategis' },
          { key: 'iku', label: 'IKU' },
          { key: 'action', label: 'Aksi' },
          { key: 'keterangan', label: 'Keterangan' },
        ]
      },
      searchTemp: '',
      filter: {
        satuan_kerja_id: satker,
        search: null,
        status: null,
      },
      isBusy: {
        getData: false,
        set: false,
      },
    }
  },

  watch: {
    filter: {
      handler() {
        this.getData()
      },
      deep: true,
    },
    searchTemp: debounce(function (search) {
      this.filter.search = search
    }, 1000),
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData(page = 1) {
      try {
        this.isBusy.getData = true

        const { data } = await axios.get(`sasaran-strategis-rpjmd/${this.id}/sasaran-strategis-pd-cross`, {
          params: {
            filter: this.filter,
            page,
          }
        })

        this.data = data
      } finally {
        this.isBusy.getData = false
      }
    },
    async set(sasaranStrategisPdId, isSet) {
      try {
        this.isBusy.set = sasaranStrategisPdId

        await axios.post(`sasaran-strategis-rpjmd/${this.id}/sasaran-strategis-pd-cross`, {
          set: isSet ? 1 : 0,
          sasaran_strategis_pd_id: sasaranStrategisPdId
        })

        this.getData(this.data.current_page)
      } finally {
        this.isBusy.set = false
      }
    }
  }
}
</script>

<template>
  <b-card>
    <b-row>
      <b-col>
        <FilterSatuanKerja v-model="filter.satuan_kerja_id" :select-props="{clearable: false}" />
      </b-col>
      <b-col>
        <b-form-group label="Cari" label-class="font-weight-bold" label-for="search">
          <b-form-input id="search" v-model="searchTemp" placeholder="Cari Sasaran Strategis/IKU"></b-form-input>
        </b-form-group>
      </b-col>
    </b-row>

    <b-table responsive hover striped :fields="table.fields" :items="data.data" :busy="isBusy.getData" show-empty class="table-bordered mt-4" head-variant="info">
      <template #cell(kegiatan)="row">
        {{ row.value.nama || '-' }}
      </template>

      <template #cell(action)="row">
        <b-button v-if="!row.item.sasaran_strategis_pd_cross_exists" @click="set(row.item.id, true)" variant="success" size="sm" class="rounded-circle" v-b-tooltip.hover title="Tambahkan" :disabled="isBusy.set == row.item.id">
          <div class="fa fa-check"></div>
        </b-button>
        <b-button v-else @click="set(row.item.id, false)" variant="danger" size="sm" class="rounded-circle" v-b-tooltip.hover title="Hapus" :disabled="isBusy.set == row.item.id">
          <div class="fa fa-times"></div>
        </b-button>
      </template>

      <template #cell(keterangan)="row">
        <span v-if="row.item.sasaran_strategis_pd_cross_exists">Sudah ditandai sebagai cross cutting</span>
      </template>
    </b-table>

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
  </b-card>
</template>