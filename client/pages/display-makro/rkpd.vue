<script>
import axios from 'axios'

export default {
  middleware: ['auth'],

  async asyncData() {
    const { data } = await axios.get('/display-makro/rkpd')

    return {
      data,
    }
  },

  data() {
    return {
      table: {
        fields: [
          { key: 'no', label: 'No' },
          { key: 'sasaran', label: 'Sasaran' },
          { key: 'iku', label: 'IKU Gubernur' },
          { key: 'target', label: 'Target' },
          { key: 'program', label: 'Program' },
          { key: 'kegiatan', label: 'Kegiatan' },
          { key: 'sub_kegiatan', label: 'Sub Kegiatan' },
          { key: 'anggaran', label: 'Anggaran' },
        ]
      },
      filter: {
        satuan_kerja_id: null,
      },
      isBusy: false,
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
    /** prepend field Satuan Kerja jika akun super atau setda */
    if (this.$role.isSuper() || this.$role.isSetda()) {
      this.table.fields.splice(1, 0, { key: 'satuan_kerja', label: 'Satuan Kerja' })
    }
  },
  
  methods: {
    async doFilter(page = 1) {
      this.isBusy = true

      const { data } = await axios.get('/display-makro/rkpd', {
        params: {
          filter: this.filter,
          page,
        }
      })

      this.data = data
      this.isBusy = false
    },
  },
}
</script>

<template>
  <b-card>
    <div>
      <b-row>
        <b-col sm="6" md="4">
          <FilterSatuanKerja v-if="$role.isSuper() || $role.isSetda()" v-model="filter.satuan_kerja_id" :is-setda="$role.isSetda()" />
        </b-col>
      </b-row>
    </div>

    <b-table sticky-header="calc(100vh - 200px)"  head-variant="info" :fields="table.fields" :items="data.data" :busy="isBusy" hover striped bordered>
      <template #cell(no)="row">
        {{ data.from + row.index }}
      </template>
      <template v-if="$role.isSuper() || $role.isSetda()" #cell(satuan_kerja)="row">
        {{ row.value.satuan_kerja_nama }}
      </template>
      <template #cell(sasaran)="row">
        {{ row.item.sasaran_strategis_rpjmd ? row.item.sasaran_strategis_rpjmd.sasaran_strategis.sasaran : '-' }}
      </template>
      <template #cell(iku)="row">
        {{ row.item.sasaran_strategis_rpjmd ? row.item.sasaran_strategis_rpjmd.indikator_sasaran_strategis.indikator : '-' }}
      </template>
      <template #cell(target)="row">
        {{ row.item.sasaran_strategis_rpjmd ? row.item.sasaran_strategis_rpjmd[$helper.getKeyTahun('target')] : '-' }}
      </template>
      <template #cell(program)="row">
        {{ row.item.kinerja_program ? row.item.kinerja_program.program.nama : '-' }}
      </template>
      <template #cell(kegiatan)="row">
        {{ row.value.nama }}
      </template>
      <template #cell(sub_kegiatan)="row">
        {{ row.value.nama }}
      </template>
      <template #cell(anggaran)="row">
        <span v-if="row.item.kinerja_program">{{ row.item.kinerja_program.anggaran | rupiah }}</span>
        <span v-else>-</span>
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
          <i class="fa fa-spinner fa-pulse fa-fw" v-if="isBusy && active"></i>
          <template v-else>{{ page }}</template>
        </template>
      </b-pagination>
    </div>
  </b-card>
</template>
