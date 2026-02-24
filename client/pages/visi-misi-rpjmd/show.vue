<template>
  <b-card>
    <b-form-group v-if="$role.isSuper() || $role.isViewAll()" label-cols="12" label-cols-md="2" label="Satuan Kerja" label-class="font-weight-bold">
      <b-form-input :value="data.satuan_kerja.satuan_kerja_nama" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Visi" label-class="font-weight-bold">
      <b-form-input :value="data.visi.visi" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Misi" label-class="font-weight-bold">
      <b-form-input :value="data.misi.misi" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Tujuan" label-class="font-weight-bold">
      <b-form-input :value="data.tujuan.tujuan" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Indikator Tujuan" label-class="font-weight-bold">
      <b-form-input :value="data.indikator_tujuan.indikator" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Satuan" label-class="font-weight-bold">
      <b-form-input :value="data.satuan" readonly></b-form-input>
    </b-form-group>

    <b-row class="mt-4">
      <b-col sm md="4">
        <b-form-group :label="`Target kinerja tahun baseline ${data.tahun_mulai - 1}`" label-class="font-weight-bold">
          <b-form-input :value="data.target_baseline" readonly></b-form-input>
        </b-form-group>
      </b-col>
      <b-col sm md="4">
        <b-form-group :label="`Realisasi kinerja tahun baseline ${data.tahun_mulai - 1}`" label-class="font-weight-bold">
          <b-form-input :value="data.realisasi_baseline || '-'" readonly></b-form-input>
        </b-form-group>
      </b-col>
      <b-col sm md="4">
        <b-form-group :label="`Capaian kinerja tahun baseline ${data.tahun_mulai - 1} (%)`" label-class="font-weight-bold">
          <b-form-input :value="data.capaian_baseline || '-'" readonly></b-form-input>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row v-for="(kinerja, index) of kinerjaTahunList" :key="index">
      <b-col sm md="4">
        <b-form-group :label="`Target kinerja tahun ${data.tahun_mulai + index}`" label-class="font-weight-bold">
          <b-form-input :value="data[kinerja.target]" readonly></b-form-input>
        </b-form-group>
      </b-col>
      <b-col sm md="4">
        <b-form-group :label="`Realisasi kinerja tahun ${data.tahun_mulai + index}`" label-class="font-weight-bold">
          <b-form-input :value="data[kinerja.realisasi] || '-'" readonly></b-form-input>
        </b-form-group>
      </b-col>
      <b-col sm md="4">
        <b-form-group :label="`Capaian kinerja tahun ${data.tahun_mulai + index} (%)`" label-class="font-weight-bold">
          <b-form-input :value="data[kinerja.capaian] || '-'" readonly></b-form-input>
        </b-form-group>
      </b-col>
    </b-row>

  </b-card>
</template>

<script>
import axios from 'axios'

export default {
  middleware: ['auth'],
  async asyncData({ params }) {
    const id = params.id
    const { data } = await axios.get(`visi-misi-rpjmd/${id}`)

    return {
      data,
    }
  },
  data() {
    let kinerjaTahunList = []

    for (let index = 1; index <= 5; index++) {
      kinerjaTahunList.push({
        target: `target_${index}`,
        realisasi: `realisasi_${index}`,
        capaian: `capaian_${index}`,
      })
    }

    return {
      kinerjaTahunList,
    }
  }
}
</script>

<style>

</style>