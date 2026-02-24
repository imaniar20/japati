<script>
import axios from 'axios'
import CatatanRekomendasiView from '@/components/CatatanRekomendasiView.vue';
import DiagramHasilAkhirLKE from '@/components/DiagramHasilAkhirLKE.vue';

export default {
  middleware: ["role-perangkat-daerah"],

  components: {
    CatatanRekomendasiView,
    DiagramHasilAkhirLKE
  },

  async asyncData() {
    const { data: { success, data: komponenList, bobotTotal, skorTotal, skorTotal2, predikat, predikat2, predikatKomponen, skorTotalPenilaianKomponen, done1, done2 } } = await axios.get("/lke/eviden/hasil-akhir");

    return {
      success,
      komponenList,
      bobotTotal,
      skorTotal,
      skorTotal2,
      predikat,
      predikat2,
      predikatKomponen,
      skorTotalPenilaianKomponen,
      done1,
      done2,
    };
  },

  computed: {
    predikatAkhir() {
      if (this.skorTotalPenilaianKomponen) {
        return this.predikatKomponen;
      } else if (this.done2) {
        return this.predikat2;
      } else {
        return this.predikat;
      }
    },
  }
}
</script>

<template>
  <div>
    <b-card>
      <div v-if="!success" class="text-center my-5">
        <h5>Penilaian belum selesai</h5>
      </div>

      <b-table-simple v-else responsive bordered hover>
        <b-thead class="text-center align-middle">
          <b-tr>
            <b-th rowspan="2" class="align-middle">No</b-th>
            <b-th rowspan="2" class="align-middle">Komponen/Sub Komponen</b-th>
            <b-th rowspan="2" class="align-middle">Bobot</b-th>
            <b-th colspan="3">Nilai</b-th>
          </b-tr>
          <b-tr>
            <b-th>Nilai Tahap Awal</b-th>
            <b-th>Nilai Tahap Akhir</b-th>
            <b-th>Nilai Pleno</b-th>
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
              <b-td><b>{{ komponen.skor_penilaian }}</b></b-td>
            </b-tr>

            <template v-for="(subKomponen) in komponen.sub_komponen">
              <b-tr :key="subKomponen.id">
                <b-td>{{ komponen.nomor }}.{{ subKomponen.nomor }}</b-td>
                <b-td>{{ subKomponen.nama }}</b-td>
                <b-td>{{ subKomponen.bobot }}</b-td>
                <b-td>{{ subKomponen.skor }}</b-td>
                <b-td>{{ subKomponen.skor2 }}</b-td>
                <b-td></b-td>
              </b-tr>
            </template>
          </template>

          <b-tr>
            <b-td colspan="2" class="text-center"><b>NILAI TOTAL</b></b-td>
            <b-td><b>{{ bobotTotal }}</b></b-td>
            <b-td><b>{{ skorTotal }}</b></b-td>
            <b-td><b>{{ skorTotal2 }}</b></b-td>
            <b-td><b>{{ skorTotalPenilaianKomponen }}</b></b-td>
          </b-tr>
          <b-tr class="bg-yellow">
            <b-td colspan="2" class="text-center">
              <b>NILAI SAKIP</b>
              <b v-if="skorTotalPenilaianKomponen"></b>
              <b v-else-if="done2">TAHAP AKHIR</b>
              <b v-else>TAHAP AWAL</b>
            </b-td>
            <b-td colspan="4" class="text-center">
              <b>{{ predikatAkhir[0] }} <br>{{ predikatAkhir[1] }}</b>
            </b-td>
          </b-tr>
          <b-tr>
            <b-td colspan="6">{{ predikatAkhir[2] }}</b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>
    </b-card>

    <DiagramHasilAkhirLKE v-if="success && skorTotalPenilaianKomponen" :satuanKerjaNama="$store.getters['auth/user'].satuan_kerja?.satuan_kerja_nama" :predikat="[predikatAkhir[0], predikatAkhir[1]]" :skor="skorTotalPenilaianKomponen" class="mt-3" />

    <CatatanRekomendasiView v-if="done2" :satker-id="$store.getters['auth/user'].satuan_kerja_id" :success="success" class="mt-3" />
  </div>
</template>

<style scoped>
.bg-yellow:hover, .bg-yellow {
  background-color: yellow;
}
</style>