<script>
import axios from 'axios'

export default {
  middleware: ['auth'],
  async asyncData({ params }) {
    const id = params.id

    const { data: form } = await axios.get(`lkip/narasi-pemda/${id}`)

    return {
      form
    }
  },

  computed: {
    narasi_full: function () {
      return `${this.form.narasi_1 || ''} ${this.form.narasi_2 || ''} ${this.form.narasi_3 || ''} ${this.form.narasi_4 || ''} ${this.form.narasi_5 || ''} ${this.form.narasi_6 || ''} ${this.form.narasi_7 || ''}`
    }
  },
}
</script>

<template>
  <b-card>
    <b-form-group
      v-if="$role.isSuper()"
      label="Satuan Kerja"
      label-for="satuan-kerja"
    >
      <b-form-input v-model="form.satuan_kerja.satuan_kerja_nama" readonly></b-form-input>
    </b-form-group>
    <b-form-group
      label="SASARAN RPJMD"
      label-for="sasaran-strategis"
    >
      <b-form-input v-model="form.sasaran_strategis.sasaran" readonly></b-form-input>
    </b-form-group>
    <b-form-group
      label="INDIKATOR KINERJA GUBERNUR"
      label-for="indikator-sasaran-strategis"
    >
      <b-form-input v-model="form.indikator_sasaran_strategis.indikator" readonly></b-form-input>
    </b-form-group>

    <b-form-group
      label="Perbandingan antara target dan realisasi kinerja tahun ini berdasarkan perjanjian kinerja kepala daerah"
      label-for="narasi-1"
    >
      <b-form-textarea
        id="narasi-1"
        v-model="form.narasi_1"
        rows="3"
        readonly
      ></b-form-textarea>
    </b-form-group>
    <b-form-group
      label="Perbandingan antara realisasi kinerja serta capaian kinerja tahun ini dengan tahun lalu dan beberapa tahun terakhir"
      label-for="narasi-2"
    >
      <b-form-textarea
        id="narasi-2"
        v-model="form.narasi_2"
        rows="3"
        readonly
      ></b-form-textarea>
    </b-form-group>
    <b-form-group
      label="Perbandingan realisasi kinerja sampai dengan tahun ini dengan target jangka menengah yang terdapat dalam dokumen perencanaan jangka menengah/RPJMD"
      label-for="narasi-3"
    >
      <b-form-textarea
        id="narasi-3"
        v-model="form.narasi_3"
        rows="3"
        readonly
      ></b-form-textarea>
    </b-form-group>
    <b-form-group
      label="Perbandingan realisasi kinerja tahun ini dengan standar nasional (jika ada)"
      label-for="narasi-4"
    >
      <b-form-textarea
        id="narasi-4"
        v-model="form.narasi_4"
        rows="3"
        readonly
      ></b-form-textarea>
    </b-form-group>
    <b-form-group
      label="Analisis penyebab keberhasilan/kegagalan atau peningkatan/penurunan kinerja serta alternatif solusi yang telah dilakukan"
      label-for="narasi-5"
    >
      <b-form-textarea
        id="narasi-5"
        v-model="form.narasi_5"
        rows="3"
        readonly
      ></b-form-textarea>
    </b-form-group>
    <b-form-group
      label="Analisis program/kegiatan yang menunjang keberhasilan ataupun kegagalan pencapaian pernyataan kinerja"
      label-for="narasi-6"
    >
      <b-form-textarea
        id="narasi-6"
        v-model="form.narasi_6"
        rows="3"
        readonly
      ></b-form-textarea>
    </b-form-group>
    <b-form-group
      label="Analisis atas efektivitas dan efisiensi penggunaan sumber daya"
      label-for="narasi-7"
    >
      <b-form-textarea
        id="narasi-7"
        v-model="form.narasi_7"
        rows="3"
        readonly
      ></b-form-textarea>
    </b-form-group>
    <b-form-group
      label="NARASI LKIP"
      label-for="capaian-kinerja"
    >
      <b-form-textarea
        id="capaian-kinerja"
        :value="narasi_full"
        rows="10"
        readonly
      ></b-form-textarea>
    </b-form-group>
  </b-card>
</template>