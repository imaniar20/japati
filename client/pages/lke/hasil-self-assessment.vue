<script>
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
  data() {
    return {
      filter: {
        satuan_kerja_id: this.$store.getters['auth/user'].satuan_kerja_id,
      },
      isBusy: {
        getData: false,
      },
      komponenList: [],
      bobotTotal: 0,
      skorTotal: 0,
      skorTotal2: 0,
      predikat: ['-', '-', '-'],
      predikat2: ['-', '-', '-'],
    }
  },

  computed: {
    ...mapGetters({
      user: 'auth/user'
    }),
    predikatAkhir() {
      if (this.skorTotal2) {
        return this.predikat2;
      } else {
        return this.predikat;
      }
    },
  },

  watch: {
    filter: {
      handler: function () {
        if (this.filter.satuan_kerja_id) this.getData()
      },
      deep: true,
    }
  },

  mounted() {
    if (this.filter.satuan_kerja_id) {
      this.getData()
    }
  },

  methods: {
    async getData() {
      this.isBusy.getData = true

      const { data: { data: komponenList, bobotTotal, skorTotal, skorTotal2, predikat, predikat2 } } = await axios.get('/lke/eviden/hasil-self-assessment', {
        params: {
          satuan_kerja_id: this.filter.satuan_kerja_id,
        }
      })

      this.komponenList = komponenList
      this.bobotTotal = bobotTotal
      this.skorTotal = skorTotal
      this.skorTotal2 = skorTotal2
      this.predikat = predikat
      this.predikat2 = predikat2

      this.isBusy.getData = false
    }
  }
}
</script>

<template>
  <b-card>
    <div v-if="$role.isValidatorLKE()">
      <FilterSatuanKerja v-model="filter.satuan_kerja_id" :selectProps="{clearable: false}" :satuanKerjaIds="user.lke_penilaian_satuan_kerja_ids" />
    </div>

    <b-table-simple v-if="komponenList.length && !isBusy.getData" responsive bordered hover>
      <b-thead>
        <b-tr>
          <b-th class="align-middle">No</b-th>
          <b-th class="align-middle">Komponen/Sub Komponen</b-th>
          <b-th class="align-middle">Bobot</b-th>
          <b-th class="align-middle">Tahap Awal</b-th>
          <b-th class="align-middle">Perbaikan</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <template v-for="(komponen) in komponenList">
          <b-tr :key="komponen.id">
            <b-td><b>{{ komponen.nomor }}</b></b-td>
            <b-td><b>{{ komponen.nama }}</b></b-td>
            <b-td><b>{{ komponen.bobot }}</b></b-td>
            <b-td><b>{{ komponen.skor }}</b></b-td>
            <b-td><b>{{ komponen.skor2 }}</b></b-td>
          </b-tr>

          <template v-for="(subKomponen) in komponen.sub_komponen">
            <b-tr :key="subKomponen.id">
              <b-td>{{ komponen.nomor }}.{{ subKomponen.nomor }}</b-td>
              <b-td>{{ subKomponen.nama }}</b-td>
              <b-td>{{ subKomponen.bobot }}</b-td>
              <b-td>{{ subKomponen.skor }}</b-td>
              <b-td>{{ subKomponen.skor2 }}</b-td>
            </b-tr>
          </template>
        </template>

        <b-tr>
          <b-td colspan="2" class="text-center"><b>NILAI TOTAL</b></b-td>
          <b-td><b>{{ bobotTotal }}</b></b-td>
          <b-td><b>{{ skorTotal }}</b></b-td>
          <b-td><b>{{ skorTotal2 }}</b></b-td>
        </b-tr>
        <b-tr class="bg-yellow">
          <b-td colspan="2" class="text-center">
            <b>NILAI SAKIP</b>
            <b v-if="skorTotal2">PERBAIKAN</b>
            <b v-else>TAHAP AWAL</b>
          </b-td>
          <b-td colspan="4" class="text-center">
            <b>{{ predikatAkhir[0] }} <br>{{ predikatAkhir[1] }}</b>
          </b-td>
        </b-tr>
        <b-tr>
          <b-td colspan="5">{{ predikatAkhir[2] }}</b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>
  </b-card>
</template>

<style scoped>
.bg-yellow:hover, .bg-yellow {
  background-color: yellow;
}
</style>