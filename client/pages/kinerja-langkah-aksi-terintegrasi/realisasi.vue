<script>
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
  middleware: ['auth'],

  data() {
    return {
      data: {
        data: [],
        from: 1,
        current_page: 1,
        total: 0,
        per_page: 20,
      },
      filter: {
        satuan_kerja_id: null,
        sasaran_sub_kegiatan: null,
      },
      isBusy: {
        doFilter: false,
      },

    }
  },

  computed: {
    ...mapGetters({
      user: 'auth/user'
    }),
  },

  watch: {
    'filter.satuan_kerja_id': function() {
      this.filter.sasaran_sub_kegiatan = null
      this.doFilter()
    },
    'filter.sasaran_sub_kegiatan': function() {
      this.doFilter()
    },
  },

  mounted() {
    // trigger get data via watcher
    this.filter.satuan_kerja_id = this.user.satuan_kerja_id

    // trigger get data saat tidak ada satuan kerja (contoh super admin)
    if (!this.filter.satuan_kerja_id) {
      this.doFilter()
    }
  },

  methods: {
    async doFilter(page = 1) {
      this.isBusy.doFilter = true

      const { data } = await axios.get('kinerja-langkah-aksi-terintegrasi', {
        params: {
          filter: this.filter,
          page,
        }
      })

      this.data = data
      this.isBusy.doFilter = false
    },
  },
}
</script>

<template>
  <b-card>
    <div>
      <b-row>
        <b-col sm="6" md="4">
          <FilterSatuanKerja v-if="$role.isSuper() || $role.isSetda() || $role.isViewAll()" v-model="filter.satuan_kerja_id" :is-setda="$role.isSetda()" />
          <FilterSasaranKinerja v-model="filter.sasaran_sub_kegiatan" model="kinerja-sub-kegiatan" :satker="filter.satuan_kerja_id" labelTitle="Filter Sasaran Sub Kegiatan" />
        </b-col>
      </b-row>
    </div>

    <b-table-simple sticky-header="calc(100vh - 200px)" :aria-busy="isBusy.doFilter" hover responsive bordered striped>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle" rowspan="2">No</b-th>
          <b-th class="align-middle" rowspan="2" v-if="$role.isSuper() || $role.isSetda() || $role.isViewAll()">Satuan Kerja</b-th>
          <b-th class="align-middle" rowspan="2">Program</b-th>
          <b-th class="align-middle" rowspan="2">Kegiatan</b-th>
          <b-th class="align-middle" rowspan="2">Sub Kegiatan</b-th>
          <b-th class="align-middle" rowspan="2">Sasaran Sub Kegiatan</b-th>
          <b-th class="align-middle" rowspan="2">Langkah Aksi</b-th>
          <b-th class="align-middle" colspan="12">Realisasi Bulan Ke</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:49px" v-for="n of 12" :key="n">{{ n }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="!data.data.length">
          <b-td :colspan="$role.isSuper() || $role.isSetda() || $role.isViewAll() ? 20 : 19">There are no records to show</b-td>
        </b-tr>
        <template v-else v-for="(item, index) of data.data">
          <b-tr v-for="(langkahAksi, langkahAksiIndex) in item.data" :key="`${index}-${langkahAksi.bulan}`">
            <td v-if="!langkahAksiIndex" :rowspan="item.data.length">{{ data.from + index }}</td>
            <td v-if="!langkahAksiIndex && ($role.isSuper() || $role.isSetda() || $role.isViewAll())" :rowspan="item.data.length">{{ item.satuan_kerja }}</td>
            <b-td v-if="!langkahAksiIndex" :rowspan="item.data.length">{{ item.program }}</b-td>
            <b-td v-if="!langkahAksiIndex" :rowspan="item.data.length">{{ item.kegiatan }}</b-td>
            <b-td v-if="!langkahAksiIndex" :rowspan="item.data.length">{{ item.sub_kegiatan }}</b-td>
            <b-td v-if="!langkahAksiIndex" :rowspan="item.data.length">{{ item.sasaran_sub_kegiatan }}</b-td>
            <b-td>{{ langkahAksi.indikator_langkah_aksi }}</b-td>

            <b-td v-for="n in 12" :key="n">
              {{ langkahAksi.bulan == n && langkahAksi.realisasi ? `${langkahAksi.realisasi} ${langkahAksi.satuan}` : '-' }}
            </b-td>
          </b-tr>
        </template>
      </b-tbody>
    </b-table-simple>

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