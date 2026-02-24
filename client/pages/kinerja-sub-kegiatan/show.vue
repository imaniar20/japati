<template>
  <b-card>
    <b-form-group v-if="$role.isSuper() || $role.isViewAll()" label-cols="12" label-cols-md="2" label="Satuan Kerja" label-class="font-weight-bold" label-for="satker">
      <b-form-input :value="data.satuan_kerja.satuan_kerja_nama" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Kegiatan" label-class="font-weight-bold" label-for="kegiatan">
      <b-form-input :value="data.kegiatan?.nama || '-'" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Sub Kegiatan" label-class="font-weight-bold" label-for="sub-kegiatan">
      <b-form-input :value="data.sub_kegiatan?.nama || '-'" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Sasaran Sub Kegiatan" label-class="font-weight-bold" label-for="sasaran">
      <b-form-input :value="data.sasaran" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Indikator Sub Kegiatan" label-class="font-weight-bold" label-for="indikator">
      <b-form-input :value="data.indikator" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Satuan" label-class="font-weight-bold" label-for="satuan">
      <b-form-input :value="data.satuan" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Target Kinerja" label-class="font-weight-bold" label-for="target">
      <b-form-input :value="data.target" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Realisasi Kinerja" label-class="font-weight-bold" label-for="realisasi">
      <b-form-input :value="data.realisasi" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Capaian Kinerja (%)" label-class="font-weight-bold" label-for="capaian">
      <b-form-input :value="data.capaian" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Pagu Anggaran" label-class="font-weight-bold" label-for="anggaran">
      <money class="form-control" :value="data.anggaran" v-bind="money" readonly></money>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Realisasi Anggaran" label-class="font-weight-bold" label-for="realisasi_anggaran">
      <money class="form-control" :value="data.realisasi_anggaran" v-bind="money" readonly></money>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Capaian Anggaran (%)" label-class="font-weight-bold" label-for="capaian_anggaran">
      <b-form-input :value="data.capaian_anggaran" readonly></b-form-input>
    </b-form-group>
    
    <b-row class="mt-5">
      <b-col xs md="6" v-for="(arrMonth, index) of arrayChunk2($const.months, 6)" :key="index">
        <b-row v-for="(month, monthIndex) of arrMonth" :key="monthIndex">
          <b-col xs sm="6">
            <b-form-group :label="`Target Kinerja ${month[1]}`" label-class="font-weight-bold" :label-for="`target-${month[0]}`">
              <b-form-input :value="data.target_bulanan[month[0]]" readonly></b-form-input>
            </b-form-group>
          </b-col>
          <b-col xs sm="6">
            <b-form-group :label="`Realisasi Kinerja ${month[1]}`" label-class="font-weight-bold" :label-for="`realisasi-${month[0]}`">
              <b-form-input :value="data.realisasi_bulanan[month[0]] || '-'" readonly></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
      </b-col>
    </b-row>

    <b-row class="mt-5">
      <b-col xs md="6" v-for="(arrMonth, index) of arrayChunk2($const.months, 6)" :key="index">
        <b-row v-for="(month, monthIndex) of arrMonth" :key="monthIndex">
          <b-col xs sm="6">
            <b-form-group :label="`Pagu Anggaran ${month[1]}`" label-class="font-weight-bold" :label-for="`anggaran-${month[0]}`">
              <money class="form-control" :value="data.anggaran_bulanan[month[0]]" v-bind="money" readonly></money>
            </b-form-group>
          </b-col>
          <b-col xs sm="6">
            <b-form-group :label="`Realisasi Anggaran ${month[1]}`" label-class="font-weight-bold" :label-for="`realisasi-anggaran-${month[0]}`">
              <money class="form-control" :value="data.realisasi_anggaran_bulanan[month[0]]" v-bind="money" readonly></money>
            </b-form-group>
          </b-col>
        </b-row>
      </b-col>
    </b-row>

    <b-form-group label-cols="12" label-cols-md="2" label="Apakah terdapat inovasi?" class="mt-4">
      <b-form-input :value="data.has_inovasi ? 'Ya' : 'Tidak'" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Uraian" label-class="font-weight-bold" label-for="inovasi-uraian">
      <b-form-input :value="data.inovasi_uraian" readonly></b-form-input>
    </b-form-group>
    <b-form-group label-cols="12" label-cols-md="2" label="Tujuan Inovasi/Permasalahan yang ingin diselesaikan melalui Inovasi" label-class="font-weight-bold" label-for="inovasi-tujuan">
      <b-form-input :value="data.inovasi_tujuan" readonly></b-form-input>
    </b-form-group>

    <b-form-group label-cols="12" label-cols-md="2" label="Foto" label-class="font-weight-bold" label-for="inovasi-lampiran">
        <div class="mt-4">
          <b-row v-for="(images, index) of arrayChunk2(data.inovasi_lampiran, 6)" :key="index">
            <b-col v-for="(image, indexImage) of images" :key="indexImage" cols="6" sm="4" md="2" class="text-center mb-3">
              <a :href="image" target="_blank">
                <b-img :src="image" thumbnail :alt="image.substring(image.lastIndexOf('/') + 1)"></b-img>
              </a>
            </b-col>
          </b-row>
        </div>
      </b-form-group>
  </b-card>
</template>

<script>
import axios from 'axios'
import { VMoney, Money } from 'v-money'
import { arrayChunk2 } from '~/plugins/utils'
import { moneyFormat } from '~/utils/formater'

export default {
  components: {
    Money,
  },
  middleware: ['auth'],
  directives: {
    money: VMoney
  },
  async asyncData({ params }) {
    const id = params.id
    const { data } = await axios.get(`kinerja-sub-kegiatan/${id}`)

    return {
      data,
    }
  },
  data() {
    return {
      money: moneyFormat,
    }
  },
  methods: {
    arrayChunk2,
  }
}
</script>