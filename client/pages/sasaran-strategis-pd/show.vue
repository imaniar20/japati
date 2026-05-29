<template>
  <b-card>
    <b-form-group v-if="$role.isSuper() || $role.isViewAll()" label-cols="12" label-cols-md="2" label="Satuan Kerja" label-class="font-weight-bold">
      <b-form-input :value="data.satuan_kerja.satuan_kerja_nama" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Sasaran Strategis RPJMD" label-class="font-weight-bold">
      <b-form-input :value="data.sasaran_strategis.sasaran" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Indikator Sasaran Strategis RPJMD/IKU Bupati" label-class="font-weight-bold">
      <b-form-input :value="data.indikator_sasaran_strategis.indikator" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Sasaran Strategis PD" label-class="font-weight-bold">
      <b-form-input :value="data.sasaran_strategis_satker" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="IKU PD" label-class="font-weight-bold">
      <b-form-input :value="data.iku" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Satuan" label-class="font-weight-bold">
      <b-form-input :value="data.satuan" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Tahun Pertama" label-class="font-weight-bold">
      <b-form-input :value="data.tahun_mulai" readonly></b-form-input>
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

    <b-form-group label-cols="12" label-cols-md="2" label="Rata-Rata Realisasi Nasional" label-class="font-weight-bold">
      <b-form-input :value="data.rata_nasional" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Peringkat di Nasional" label-class="font-weight-bold">
      <b-form-input :value="data.peringkat_nasional" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Narasi Keberhasilan/Narasi Ketidakberhasilan" label-class="font-weight-bold">
      <b-form-input :value="data.narasi_keberhasilan" readonly></b-form-input>
    </b-form-group>
    <h5 class="mb-3">Penghargaan Tahun Berjalan</h5>
    <b-form-group label-cols="12" label-cols-md="2" label="Redaksi" label-class="font-weight-bold">
      <b-form-input :value="data.redaksi" readonly></b-form-input>
    </b-form-group>

    <b-form-group label-cols="12" label-cols-md="2" label="Lampiran" label-class="font-weight-bold">
        <div class="mt-4">
          <b-row v-for="(images, index) of arrayChunk2(data.lampiran, 6)" :key="index">
            <b-col v-for="(image, indexImage) of images" :key="indexImage" cols="6" sm="4" md="2" class="text-center mb-3">
              <a :href="image" target="_blank">
                <b-img thumbnail :src="image" :alt="image.substring(image.lastIndexOf('/') + 1)"></b-img>
              </a>
            </b-col>
          </b-row>
        </div>
      </b-form-group>
  </b-card>
</template>

<script>
import axios from 'axios'
import { arrayChunk2 } from '~/plugins/utils'

export default {
  middleware: ['auth'],
  async asyncData({ params }) {
    const id = params.id
    const { data } = await axios.get(`sasaran-strategis-pd/${id}`)

    return {
      data,
      id,
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
  },
  methods: {
    arrayChunk2,
  }
}
</script>