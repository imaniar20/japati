<template>
  <div>
    <b-card>
      <form @submit.prevent="update()">
        <b-form-group v-if="$role.isSuper()" label-class="font-weight-bold pt-0" label-cols="12" label-cols-md="2" label="Satuan Kerja" label-for="satuan-kerja">
          <b-form-input :value="form.satuan_kerja.satuan_kerja_nama" plaintext></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Relasi Sasaran Strategis RPJMD & IKU Bupati" label-class="font-weight-bold" label-for="sasaran_strategis_rpjmd">
          <v-select id="sasaran_strategis_rpjmd" 
            v-model="form.sasaran_strategis_rpjmd_id" 
            :options="sasaranStrategisRpjmd" 
            :reduce="opt => opt.id" 
            label="id" 
            placeholder="Pilih relasi"
            :clearable="false"
            :filterBy="$helper.vSelectFilterBy('sasaran_strategis.sasaran', 'indikator_sasaran_strategis.indikator')"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                v-bind="attributes"
                v-on="events"
              />
            </template>
            <template #option="opt">
              <div class="border-bottom py-2 my-1">
                <div>Sasaran strategis: <b>{{ opt.sasaran_strategis.sasaran }}</b></div>
                <div>IKU Gubernur: <b>{{ opt.indikator_sasaran_strategis.indikator }}</b></div>
                <div>Capaian {{ $helper.getTahunKinerja() }}: <b>{{ opt[$helper.getKeyTahun('capaian')] ? opt[$helper.getKeyTahun('capaian')] + ' %' : '-' }}</b></div>
                <div>Target {{ $helper.getTahunKinerja() }}: <b>{{ opt[$helper.getKeyTahun('target')] }}</b></div>
              </div>
            </template>
            <template #selected-option="opt">
              <div style="display: flex; align-items: baseline">
                <div v-if="opt.sasaran_strategis && opt.indikator_sasaran_strategis">{{ opt.sasaran_strategis.sasaran }} | {{ opt.indikator_sasaran_strategis.indikator }}</div>
              </div>
            </template>
          </v-select>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Relasi Sasaran Strategis Perangkat Daerah " label-class="font-weight-bold" label-for="sasaran_strategis_pd">
          <v-select id="sasaran_strategis_pd" 
            v-model="form.sasaran_strategis_pd_id" 
            :options="sasaranStrategisPd" 
            :reduce="opt => opt.id" 
            label="id" 
            placeholder="Pilih relasi"
            :clearable="false"
            :filterBy="$helper.vSelectFilterBy('sasaran_strategis_satker', 'iku')"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                v-bind="attributes"
                v-on="events"
              />
            </template>
            <template #option="opt">
              <div class="border-bottom py-2 my-1">
                <div>Sasaran strategis: <b>{{ opt.sasaran_strategis_satker }}</b></div>
                <div>Indikator: <b>{{ opt.iku }}</b></div>
              </div>
            </template>
            <template #selected-option="opt">
              <div style="display: flex; align-items: baseline">
                <div>{{ opt.sasaran_strategis_satker }} | {{ opt.iku }}</div>
              </div>
            </template>
          </v-select>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Relasi Kinerja Program" label-class="font-weight-bold" label-for="kinerja_program">
          <v-select id="kinerja_program" 
            v-model="form.kinerja_program_id" 
            :options="kinerjaProgram" 
            :reduce="opt => opt.id" 
            label="id" 
            placeholder="Pilih relasi"
            :clearable="false"
            :filterBy="$helper.vSelectFilterBy('program.nama', 'indikator')"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                v-bind="attributes"
                v-on="events"
              />
            </template>
            <template #option="opt">
              <div class="border-bottom py-2 my-1">
                <div>Program: <b>{{ opt.program ? opt.program.nama : '-' }}</b></div>
                <div>Indikator program: <b>{{ opt.indikator }}</b></div>
                <div>Pagu anggaran: <b>{{ opt.anggaran | rupiah }}</b></div>
              </div>
            </template>
            <template #selected-option="opt">
              <div style="display: flex; align-items: baseline">
                <div v-if="opt.program">{{ opt.program.nama }} | {{ opt.indikator }}</div>
              </div>
            </template>
          </v-select>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Relasi Kinerja Kegiatan" label-class="font-weight-bold" label-for="kinerja_kegiatan">
          <v-select id="kinerja_kegiatan" 
            v-model="form.kinerja_kegiatan_id" 
            :options="kinerjaKegiatan" 
            :reduce="opt => opt.id" 
            label="id" 
            placeholder="Pilih relasi"
            :clearable="false"
            :filterBy="$helper.vSelectFilterBy('kegiatan.nama', 'indikator')"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                v-bind="attributes"
                v-on="events"
              />
            </template>
            <template #option="opt">
              <div class="border-bottom py-2 my-1">
                <div>Kegiatan: <b>{{ opt.kegiatan ? opt.kegiatan.nama :'-' }}</b></div>
                <div>Indikator kegiatan: <b>{{ opt.indikator }}</b></div>
                <div>Pagu anggaran: <b>{{ opt.anggaran | rupiah }}</b></div>
              </div>
            </template>
            <template #selected-option="opt">
              <div style="display: flex; align-items: baseline">
                <div v-if="opt.kegiatan">{{ opt.kegiatan.nama }} | {{ opt.indikator }}</div>
              </div>
            </template>
          </v-select>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Relasi Kinerja Sub Kegiatan" label-class="font-weight-bold" label-for="kinerja_sub_kegiatan">
          <v-select id="kinerja_sub_kegiatan" 
            v-model="form.kinerja_sub_kegiatan_id" 
            :options="kinerjaSubKegiatan" 
            :reduce="opt => opt.id" 
            label="id" 
            placeholder="Pilih relasi"
            :clearable="false"
            :filterBy="$helper.vSelectFilterBy('sub_kegiatan.nama', 'indikator')"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                v-bind="attributes"
                v-on="events"
              />
            </template>
            <template #option="opt">
              <div class="border-bottom py-2 my-1">
                <div>Sub kegiatan: <b>{{ opt.sub_kegiatan ? opt.sub_kegiatan.nama : '-' }}</b></div>
                <div>Indikator sub kegiatan: <b>{{ opt.indikator }}</b></div>
              </div>
            </template>
            <template #selected-option="opt">
              <div style="display: flex; align-items: baseline">
                <div v-if="opt.sub_kegiatan">{{ opt.sub_kegiatan.nama }} | {{ opt.indikator }}</div>
              </div>
            </template>
          </v-select>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Sub Kegiatan" label-class="font-weight-bold" label-for="sub-kegiatan">
          <v-select id="sub-kegiatan" 
            v-model="form.sub_kegiatan_id" 
            :options="subKegiatan" 
            :reduce="opt => opt.id" 
            label="nama" 
            placeholder="Pilih sub kegiatan"
            :clearable="false"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                :required="!form.sub_kegiatan_id"
                v-bind="attributes"
                v-on="events"
              />
            </template>
          </v-select>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Langkah Aksi" label-class="font-weight-bold" label-for="langkah-aksi">
          <b-form-input id="langkah-aksi" v-model="form.langkah_aksi" required></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Sasaran Langkah Aksi" label-class="font-weight-bold" label-for="sasaran">
          <b-form-input id="sasaran" v-model="form.sasaran"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Indikator Langkah Aksi" label-class="font-weight-bold" label-for="indikator">
          <b-form-input id="indikator" v-model="form.indikator" required></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Satuan" label-class="font-weight-bold" label-for="satuan">
          <b-form-input id="satuan" v-model="form.satuan" required></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Target Kinerja" label-class="font-weight-bold" label-for="target">
          <b-form-input id="target" v-model="form.target" required></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Realisasi Kinerja" label-class="font-weight-bold" label-for="realisasi">
          <b-form-input id="realisasi" v-model="form.realisasi"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Capaian Kinerja (%)" label-class="font-weight-bold" label-for="capaian">
          <b-form-input type="number" step="0.01" id="capaian" v-model="form.capaian"></b-form-input>
        </b-form-group>
        <b-form-group v-if="form.capaian < 100" label-cols="12" label-cols-md="2" label="Penyebab Kegagalan" label-class="font-weight-bold" label-for="penyebab_kegagalan">
          <b-form-textarea id="penyebab_kegagalan" v-model="form.penyebab_kegagalan" placeholder="Tuliskan penyebab kegagalan" rows="3"></b-form-textarea>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Pagu Anggaran" label-class="font-weight-bold" label-for="anggaran">
          <money class="form-control" id="anggaran" v-model="form.anggaran" v-bind="money" required></money>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Realisasi Anggaran" label-class="font-weight-bold" label-for="realisasi_anggaran">
          <money class="form-control" id="realisasi_anggaran" v-model="form.realisasi_anggaran" v-bind="money" required disabled></money>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Capaian Anggaran (%)" label-class="font-weight-bold" label-for="capaian_anggaran">
          <b-form-input type="number" step="0.01" id="capaian_anggaran" v-model="form.capaian_anggaran" disabled></b-form-input>
        </b-form-group>
        
        <b-row class="mt-5">
          <b-col xs md="6" v-for="(arrMonth, index) of arrayChunk2($const.months, 6)" :key="index">
            <b-row v-for="(month, monthIndex) of arrMonth" :key="monthIndex">
              <b-col xs sm="6">
                <b-form-group :label="`Target Kinerja ${month[1]}`" label-class="font-weight-bold" :label-for="`target-${month[0]}`">
                  <b-form-input :id="`target-${month[0]}`" v-model="form.target_bulanan[month[0]]" required></b-form-input>
                </b-form-group>
              </b-col>
              <b-col xs sm="6">
                <b-form-group :label="`Realisasi Kinerja ${month[1]}`" label-class="font-weight-bold" :label-for="`realisasi-${month[0]}`">
                  <b-form-input :id="`realisasi-${month[0]}`" v-model="form.realisasi_bulanan[month[0]]"></b-form-input>
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
                  <money class="form-control" :id="`anggaran-${month[0]}`" v-model="form.anggaran_bulanan[month[0]]"  v-bind="money"></money>
                </b-form-group>
              </b-col>
              <b-col xs sm="6">
                <b-form-group :label="`Realisasi Anggaran ${month[1]}`" label-class="font-weight-bold" :label-for="`realisasi-anggaran-${month[0]}`">
                  <money class="form-control" :id="`realisasi-anggaran-${month[0]}`" v-model="form.realisasi_anggaran_bulanan[month[0]]" v-bind="money"></money>
                </b-form-group>
              </b-col>
            </b-row>
          </b-col>
        </b-row>

        <div class="text-right mt-5">
          <b-button variant="primary" :disabled="isBusy" type="submit">
            <i v-if="isBusy" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
            <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
            Simpan
          </b-button>
        </div>
      </form>
    </b-card>

    <b-card title="Diagnosa Critical Success Factor (CSF) Gagal" class="mt-2">
      <KinerjaTidakTercapai type="kinerja-langkah-aksi" :id="id" />
    </b-card>

    <b-card title="Solusi dari Sasaran Kinerja Tahun Lalu:" class="mt-2">
      <SolusiKinerja type="kinerja-langkah-aksi" :id="id" :solusi="form.solusi" />
    </b-card>
  </div>
</template>

<script>
import axios from 'axios'
import { mapGetters } from 'vuex'
import Swal from 'sweetalert2'
import { Money } from 'v-money'
import { arrayChunk2 } from '~/plugins/utils'
import KinerjaTidakTercapai from '~/components/KinerjaTidakTercapai.vue'
import SolusiKinerja from '~/components/SolusiKinerja.vue'
import { moneyFormat } from '~/utils/formater'

export default {
  middleware: ['auth', 'role-perangkat-daerah'],
  components: {
    Money,
    KinerjaTidakTercapai,
    SolusiKinerja,
  },
  async asyncData({ params }) {
    const id = parseInt(params.id)
    const { data: {
      satker,
      subKegiatan,
      form,
      sasaranStrategisRpjmd,
      sasaranStrategisPd,
      kinerjaProgram,
      kinerjaKegiatan,
      kinerjaSubKegiatan,
    }} = await axios.get(`kinerja-langkah-aksi/${id}/edit`)

    return {
      satker,
      subKegiatan,
      form,
      id,
      sasaranStrategisRpjmd,
      sasaranStrategisPd,
      kinerjaProgram,
      kinerjaKegiatan,
      kinerjaSubKegiatan,
    }
  },
  data() {
    return {
      isBusy: false,
      isKinerjaSubKegiatanBusy: false,
      money: moneyFormat,
    }
  },
  computed: {
    ...mapGetters({
      user: 'auth/user'
    }),
    realisasiAnggaran: function () {
      return Object.values(this.form.realisasi_anggaran_bulanan).reduce((acc, next) => acc + next)
    },
    capaianAnggaran: function () {
      return Math.round(((this.realisasiAnggaran / this.form.anggaran * 100) + Number.EPSILON) * 1000) / 1000
    },
  },
  methods: {
    arrayChunk2,
    update() {
      this.isBusy = true

      axios.patch(`kinerja-langkah-aksi/${this.id}`, this.form)
      .then(() => {
        Swal.fire({
          type: 'success',
          title: 'Berhasil menyimpan data'
        })
      }).catch(() => {

        Swal.fire({
          type: 'error',
          title: 'Gagal menyimpan data!',
        })
      }).then(() => this.isBusy = false)
    },
  },
  watch: {
    realisasiAnggaran: function (newVal) {
      this.form.realisasi_anggaran = newVal
    },
    capaianAnggaran: function (newVal) {
      this.form.capaian_anggaran = newVal
    },
  },
}
</script>