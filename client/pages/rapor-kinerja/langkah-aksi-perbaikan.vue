<script>
import axios from 'axios'
import TriwulanOption from '~/components/TriwulanOption.vue'

export default {
  middleware: ['auth'],

  components: {
    TriwulanOption,
  },

  async asyncData({ params, redirect }) {
    const triwulan = parseInt(params.triwulan)
    
    if (![1, 2, 3, 4].includes(triwulan)) {
      redirect('/rapor-kinerja/1/langkah-aksi-perbaikan')
      return false
    }

    return {
      triwulan,
    }
  },

  data() {
    let satkerId = null

    if (!this.$role.isSuper() && !this.$role.isviewRaporKinerja()) {
      satkerId = this.$store.getters['auth/user'].satuan_kerja_id
    }

    return {
      filter: {
        satuan_kerja_id: satkerId,
      },
      isBusy: {
        getData: false,
      },
      data: [],
    }
  },

  watch: {
    triwulan: function (val) {
      this.$router.push(`/rapor-kinerja/${val}/langkah-aksi-perbaikan`)
    },
    filter: {
      handler: function (val) {
        if (val.satuan_kerja_id) {
          this.getData()
        }
      },
      deep: true,
    },
  },

  mounted() {
    if (this.filter.satuan_kerja_id) {
      this.getData()
    }
  },

  methods: {
    async getData() {
      this.isBusy.getData = true

      try {
        const { data } = await axios.get(`/rapor-kinerja/${this.triwulan}/langkah-aksi-perbaikan`, {
          params: this.filter
        })

        this.data = data
      } catch (error) {
        alert('Gagal mengambil data rapor kinerja')
      } finally {
        this.isBusy.getData = false
      }
    }
  }
}
</script>

<template>
  <b-card>
    <b-row>
      <b-col v-if="$role.isSuper() || $role.isviewRaporKinerja()">
        <FilterSatuanKerja v-model="filter.satuan_kerja_id" labelTitle="Satuan Kerja" />
      </b-col>
      <b-col>
        <TriwulanOption v-model="triwulan" />
      </b-col>
    </b-row>

    <b-table-simple :aria-busy="isBusy.getData" striped hover responsive bordered class="mt-3">
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle">No</b-th>
          <b-th class="align-middle">Sub Kegiatan Gagal</b-th>
          <b-th class="align-middle">Sasaran Sub Kegiatan Gagal</b-th>
          <b-th class="align-middle">Indikator Sub Kegiatan Gagal</b-th>
          <b-th class="align-middle">Langkah Aksi Sub Kegiatan Gagal</b-th>
          <b-th class="align-middle">Penyebab Kegagalan</b-th>
          <b-th class="align-middle">Langkah Aksi Perbaikan</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="!data.length">
          <b-td colspan="7">There are no records to show</b-td>
        </b-tr>
        <template v-for="(byKinerja, indexKinerja) of data">
          <b-tr v-for="(penyebab, indexPenyebab) of byKinerja.penyebab" :key="`${indexKinerja}-${indexPenyebab}`">
            <b-td v-if="indexPenyebab == 0" :rowspan="byKinerja.penyebab.length">{{ indexKinerja + 1 }}</b-td>
            <b-td v-if="indexPenyebab == 0" :rowspan="byKinerja.penyebab.length">{{ byKinerja.sub_kegiatan }}</b-td>
            <b-td v-if="indexPenyebab == 0" :rowspan="byKinerja.penyebab.length">{{ byKinerja.sasaran }}</b-td>
            <b-td v-if="indexPenyebab == 0" :rowspan="byKinerja.penyebab.length">{{ byKinerja.indikator }}</b-td>
            <b-td v-if="indexPenyebab == 0" :rowspan="byKinerja.penyebab.length">
              <ol class="pl-3">
                <li v-for="langkahAksi of byKinerja.langkah_aksi_terintegrasi">{{ langkahAksi }}</li>
              </ol>
            </b-td>
            <b-td>{{ penyebab.penyebab }}</b-td>
            <b-td>
              <ol class="pl-3">
                <li v-for="langkahAksi of penyebab.langkah_aksi">{{ langkahAksi.langkah_aksi }}</li>
              </ol>
            </b-td>
          </b-tr>
        </template>
      </b-tbody>
    </b-table-simple>
  </b-card>
</template>