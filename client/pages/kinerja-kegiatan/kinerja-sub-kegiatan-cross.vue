<script>
import axios from 'axios';

export default {
  middleware: [
    'auth',
    'role-perangkat-daerah',
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
          { key: 'sasaran', label: 'Sasaran' },
          { key: 'indikator', label: 'Indikator' },
          { key: 'sub_kegiatan', label: 'Sub Kegiatan' },
          { key: 'pengampu', label: 'Pengampu' },
          { key: 'action', label: 'Aksi' },
          { key: 'keterangan', label: 'Keterangan' },
        ]
      },
      filter: {
        satuan_kerja_id: satker,
        sasaran: null,
        indikator: null,
        status: null,
        pengampu: null,
        tim_kerja_id: null,
        v_struktur_organisasi_id: null,
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
    }
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData(page = 1) {
      try {
        this.isBusy.getData = true

        const { data } = await axios.get(`kinerja-kegiatan/${this.id}/kinerja-sub-kegiatan-cross`, {
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
    async set(kinerjaSubKegiatanId, isSet) {
      try {
        this.isBusy.set = kinerjaSubKegiatanId

        await axios.post(`kinerja-kegiatan/${this.id}/kinerja-sub-kegiatan-cross`, {
          set: isSet ? 1 : 0,
          kinerja_sub_kegiatan_id: kinerjaSubKegiatanId
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
        <FilterPengampu v-model="filter.pengampu" v-if="filter.satuan_kerja_id" />
        <FilterPengampuTimKerja v-if="filter.pengampu == 'tim-kerja' && filter.satuan_kerja_id" v-model="filter.tim_kerja_id" :satuan-kerja-id="filter.satuan_kerja_id" />
        <FilterPengampuUnitKerja v-if="filter.pengampu == 'unit-kerja' && filter.satuan_kerja_id" v-model="filter.v_struktur_organisasi_id" :satuan-kerja-id="filter.satuan_kerja_id" type="kinerja-sub-kegiatan" />
      </b-col>
      <b-col>
        <FilterSasaranKinerja v-model="filter.sasaran" model="kinerja-sub-kegiatan" :satker="filter.satuan_kerja_id" />
        <FilterIndikatorKinerja v-model="filter.indikator" model="kinerja-sub-kegiatan" :satker="filter.satuan_kerja_id" />
      </b-col>
    </b-row>

    <b-table responsive hover striped :fields="table.fields" :items="data.data" :busy="isBusy.getData" show-empty class="table-bordered mt-4" head-variant="info">
      <template #cell(sub_kegiatan)="row">
        {{ row.value.nama || '-' }}
      </template>

      <template #cell(pengampu)="row">
        <span v-if="row.value == 'unit-kerja'">{{ row.item?.struktur_organisasi?.jabatan_nama }}</span>
        <span v-else-if="row.value == 'tim-kerja'">{{ row.item?.tim_kerja?.nama }} - {{ row.item?.tim_kerja?.ketua?.peg_nama }}</span>
        <span v-else>-</span>
      </template>

      <template #cell(action)="row">
        <b-button v-if="!row.item.kinerja_sub_kegiatan_cross_exists" @click="set(row.item.id, true)" variant="success" size="sm" class="rounded-circle" v-b-tooltip.hover title="Tambahkan" :disabled="isBusy.set == row.item.id">
          <div class="fa fa-check"></div>
        </b-button>
        <b-button v-else @click="set(row.item.id, false)" variant="danger" size="sm" class="rounded-circle" v-b-tooltip.hover title="Hapus" :disabled="isBusy.set == row.item.id">
          <div class="fa fa-times"></div>
        </b-button>
      </template>

      <template #cell(keterangan)="row">
        <span v-if="row.item.kinerja_sub_kegiatan_cross_exists">Sudah ditandai sebagai cross cutting</span>
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