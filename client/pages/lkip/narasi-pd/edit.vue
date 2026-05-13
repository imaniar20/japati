<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  middleware: ['role-perangkat-daerah'],
  async asyncData({ params }) {
    const id = params.id

    const {data: {
      sasaranStrategisList,
      indikatorSasaranStrategisList,
      sasaranStrategisPD,
      ikuPD,
      form,
    }} = await axios.get(`lkip/narasi-pd/${id}/edit`)

    return {
      sasaranStrategisList,
      indikatorSasaranStrategisList,
      sasaranStrategisPD,
      ikuPD,
      form,
      id,
    }
  },

  data() {
    return {
      isBusy: false,
    }
  },

  computed: {
    narasi_full: function () {
      return `${this.form.narasi_1 || ''} ${this.form.narasi_2 || ''} ${this.form.narasi_3 || ''} ${this.form.narasi_4 || ''} ${this.form.narasi_5 || ''} ${this.form.narasi_6 || ''} ${this.form.narasi_7 || ''}`
    }
  },

  methods: {
    update() {
      this.isBusy = true

      axios.patch(`lkip/narasi-pd/${this.id}`, this.form)
        .then(() => {
          Swal.fire({
            type: 'success',
            title: 'Berhasil simpan data'
          })
        }).catch((err) => {
          Swal.fire({
            type: 'error',
            title: 'Gagal simpan data!',
          })
        }).then(() => this.isBusy = false)
    }
  }
}
</script>

<template>
  <b-card>
    <b-form-group
      label="SASARAN RPJMD"
      label-for="sasaran-strategis"
    >
      <v-select id="sasaran-strategis"
        v-model="form.sasaran_strategis_id"
        :options="sasaranStrategisList" 
        :reduce="opt => opt.id" 
        label="sasaran" 
        placeholder="Pilih Sasaran Strategis RPJMD"
        :clearable="false"
      >
        <template #search="{attributes, events}">
          <input
            class="vs__search"
            :required="!form.sasaran_strategis_id"
            v-bind="attributes"
            v-on="events"
          />
        </template>
      </v-select>
    </b-form-group>
    <b-form-group
      label="INDIKATOR KInerja Bupati"
      label-for="indikator-sasaran-strategis"
    >
      <v-select id="indikator-sasaran-strategis"
        v-model="form.indikator_sasaran_strategis_id"
        :options="indikatorSasaranStrategisList" 
        :reduce="opt => opt.id" 
        label="indikator" 
        placeholder="Pilih IKU"
        :clearable="false"
      >
        <template #search="{attributes, events}">
          <input
            class="vs__search"
            :required="!form.indikator_sasaran_strategis_id"
            v-bind="attributes"
            v-on="events"
          />
        </template>
      </v-select>
    </b-form-group>
    <b-form-group
      label="INDIKATOR PERANGKAT DAERAH"
      label-for="indikator-sasaran-strategis-pd"
    >
      <v-select id="indikator-sasaran-strategis-pd"
        v-model="form.iku_pd"
        :options="ikuPD" 
        placeholder="Pilih Indikator PD"
        :clearable="false"
      >
        <template #search="{attributes, events}">
          <input
            class="vs__search"
            :required="!form.iku_pd"
            v-bind="attributes"
            v-on="events"
          />
        </template>
      </v-select>
    </b-form-group>
    <b-form-group
      label="SASARAN PERANGKAT DAERAH"
      label-for="sasaran-strategis-pd"
    >
      <v-select id="sasaran-strategis-pd"
        v-model="form.sasaran_strategis_pd"
        :options="sasaranStrategisPD" 
        placeholder="Pilih Sasaran PD"
        :clearable="false"
      >
        <template #search="{attributes, events}">
          <input
            class="vs__search"
            :required="!form.sasaran_strategis_pd"
            v-bind="attributes"
            v-on="events"
          />
        </template>
      </v-select>
    </b-form-group>

    <b-form-group
      label="Perbandingan antara target dan realisasi kinerja tahun ini berdasarkan perjanjian kinerja kepala daerah"
      label-for="narasi-1"
    >
      <b-form-textarea
        id="narasi-1"
        v-model="form.narasi_1"
        rows="3"
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

    <div class="text-right">
      <div class="text-right mt-5">
        <b-button variant="primary" :disabled="isBusy" type="submit" @click="update()">
          <i v-if="isBusy" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
          <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
          Simpan
        </b-button>
      </div>
    </div>
  </b-card>
</template>