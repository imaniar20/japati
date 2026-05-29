<template>
  <b-card>
    <b-alert v-if="Boolean(penyebabKegagalan)" show variant="warning">
      Kinerja Langkah Aksi ini merupakan bagian dari Penyebab Kegagalan Triwulan {{ penyebabKegagalan.triwulan }} <b>{{ penyebabKegagalan.penyebab }}</b>
    </b-alert>
    <form @submit.prevent="store()">
      <OptionSatuanKerja v-if="$role.isSuper()" v-model="form.satuan_kerja_id" />
      
      <div v-if="form.satuan_kerja_id">
        <b-form-group label-cols="12" label-cols-md="2" label="Relasi Sasaran Strategis RPJMD & IKU Bupati" label-class="font-weight-bold" label-for="sasaran_strategis_rpjmd">
          <v-select id="sasaran_strategis_rpjmd" 
            v-model="form.sasaran_strategis_rpjmd_id" 
            :options="sasaranStrategisRpjmd" 
            :reduce="opt => opt.id" 
            label="id" 
            placeholder="Pilih relasi"
            :clearable="false"
            :disabled="Boolean(penyebabKegagalan)"
            :filterBy="$helper.vSelectFilterBy('sasaran_strategis.sasaran', 'indikator_sasaran_strategis.indikator')"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                :required="!form.sasaran_strategis_rpjmd_id"
                v-bind="attributes"
                v-on="events"
              />
            </template>
            <template #option="opt">
              <div class="border-bottom py-2 my-1">
                <div>Sasaran strategis: <b>{{ opt.sasaran_strategis.sasaran }}</b></div>
                <div>IKU Bupati: <b>{{ opt.indikator_sasaran_strategis.indikator }}</b></div>
                <div>Capaian {{ $helper.getTahunKinerja() }}: <b>{{ opt[$helper.getKeyTahun('capaian')] ? opt[$helper.getKeyTahun('capaian')] + ' %' : '-' }}</b></div>
                <div>Target {{ $helper.getTahunKinerja() }}: <b>{{ opt[$helper.getKeyTahun('target')] }}</b></div>
              </div>
            </template>
            <template #selected-option="opt">
              <div style="display: flex; align-items: baseline">
                <div>{{ opt.sasaran_strategis.sasaran }} | {{ opt.indikator_sasaran_strategis.indikator }}</div>
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
            :disabled="Boolean(penyebabKegagalan)"
            :filterBy="$helper.vSelectFilterBy('sasaran_strategis_satker', 'iku')"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                :required="!form.sasaran_strategis_pd_id"
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
            :disabled="Boolean(penyebabKegagalan)"
            :filterBy="$helper.vSelectFilterBy('program.nama', 'indikator')"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                :required="!form.kinerja_program_id"
                v-bind="attributes"
                v-on="events"
              />
            </template>
            <template #option="opt">
              <div class="border-bottom py-2 my-1">
                <div>Program: <b>{{ opt.program.nama }}</b></div>
                <div>Indikator program: <b>{{ opt.indikator }}</b></div>
                <div>Pagu anggaran: <b>{{ opt.anggaran | rupiah }}</b></div>
              </div>
            </template>
            <template #selected-option="opt">
              <div style="display: flex; align-items: baseline">
                <div>{{ opt.program.nama }} | {{ opt.indikator }}</div>
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
            :disabled="Boolean(penyebabKegagalan)"
            :filterBy="$helper.vSelectFilterBy('kegiatan.nama', 'indikator')"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                :required="!form.kinerja_kegiatan_id"
                v-bind="attributes"
                v-on="events"
              />
            </template>
            <template #option="opt">
              <div class="border-bottom py-2 my-1">
                <div>Kegiatan: <b>{{ opt.kegiatan?.nama || '-' }}</b></div>
                <div>Indikator kegiatan: <b>{{ opt.indikator }}</b></div>
                <div>Pagu anggaran: <b>{{ opt.anggaran | rupiah }}</b></div>
              </div>
            </template>
            <template #selected-option="opt">
              <div style="display: flex; align-items: baseline">
                <div>{{ opt.kegiatan?.nama || '-' }} | {{ opt.indikator }}</div>
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
            :disabled="Boolean(penyebabKegagalan)"
            :filterBy="$helper.vSelectFilterBy('sub_kegiatan.nama', 'indikator')"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                :required="!form.kinerja_sub_kegiatan_id"
                v-bind="attributes"
                v-on="events"
              />
            </template>
            <template #option="opt">
              <div class="border-bottom py-2 my-1">
                <div>Sub kegiatan: <b>{{ opt.sub_kegiatan?.nama || '-' }}</b></div>
                <div>Indikator sub kegiatan: <b>{{ opt.indikator }}</b></div>
              </div>
            </template>
            <template #selected-option="opt">
              <div style="display: flex; align-items: baseline">
                <div>{{ opt.sub_kegiatan?.nama || '-' }} | {{ opt.indikator }}</div>
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
            :disabled="Boolean(penyebabKegagalan)"
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
      </div>
    </form>
  </b-card>
</template>

<script>
import axios from 'axios'
import { mapGetters } from 'vuex'
import Swal from 'sweetalert2'
import { Money } from 'v-money'
import { arrayChunk2 } from '~/plugins/utils'
import { moneyFormat } from '~/utils/formater'

export default {
  middleware: ['auth', 'role-perangkat-daerah'],
  components: {
    Money,
  },
  async asyncData({ query }) {
    const { data: {
      subKegiatan,
      sasaranStrategisRpjmd,
      sasaranStrategisPd,
      kinerjaProgram,
      kinerjaKegiatan,
      kinerjaSubKegiatan,
      penyebabKegagalan,
    }} = await axios.get('kinerja-langkah-aksi/create', {
      params: {
        penyebab_kegagalan_id: query.penyebab_kegagalan_id
      }
    })

    return {
      subKegiatan,
      sasaranStrategisRpjmd,
      sasaranStrategisPd,
      kinerjaProgram,
      kinerjaKegiatan,
      kinerjaSubKegiatan,
      penyebabKegagalan,
    }
  },
  data() {
    let target_bulanan = {}
    let realisasi_bulanan = {}
    let anggaran_bulanan = {}
    let realisasi_anggaran_bulanan = {}

    this.$const.months.forEach(month => {
      target_bulanan[month[0]] = null
      realisasi_bulanan[month[0]] = null
      anggaran_bulanan[month[0]] = 0
      realisasi_anggaran_bulanan[month[0]] = 0
    });
    
    return {
      form: {
        satuan_kerja_id: null,
        sub_kegiatan_id: null,
        langkah_aksi: null,
        sasaran: null,
        indikator: null,
        satuan: null,
        target: null,
        target_bulanan,
        anggaran: 0,
        anggaran_bulanan,
        realisasi_bulanan,
        realisasi_anggaran_bulanan,
        realisasi: null,
        capaian: null,
        realisasi_anggaran: 0,
        capaian_anggaran: null,
        sasaran_strategis_rpjmd_id: null,
        sasaran_strategis_pd_id: null,
        kinerja_program_id: null,
        kinerja_kegiatan_id: null,
        kinerja_sub_kegiatan_id: null,
        penyebab_kegagalan_id: null,
      },
      isBusy: false,
      isKinerjaSubKegiatanBusy: false,
      money: moneyFormat,
    }
  },
  created() {
    /** set default satuan kerja */
    this.form.satuan_kerja_id = this.user.satuan_kerja_id

    if (this.penyebabKegagalan) {
      this.form.sasaran_strategis_rpjmd_id = this.penyebabKegagalan.kinerja_sub_kegiatan.sasaran_strategis_rpjmd_id
      this.form.sasaran_strategis_pd_id = this.penyebabKegagalan.kinerja_sub_kegiatan.sasaran_strategis_pd_id
      this.form.kinerja_program_id = this.penyebabKegagalan.kinerja_sub_kegiatan.kinerja_program_id
      this.form.kinerja_kegiatan_id = this.penyebabKegagalan.kinerja_sub_kegiatan.kinerja_kegiatan_id
      this.form.kinerja_sub_kegiatan_id = this.penyebabKegagalan.kinerja_sub_kegiatan.id
      this.form.sub_kegiatan_id = this.penyebabKegagalan.kinerja_sub_kegiatan.sub_kegiatan_id
      this.form.penyebab_kegagalan_id = this.penyebabKegagalan.id
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
    store() {
      this.isBusy = true

      axios.post('kinerja-langkah-aksi', this.form)
      .then(() => {
        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        }).then(() => {
          if (this.penyebabKegagalan) {
            this.$router.push(`/rapor-kinerja/${this.penyebabKegagalan.triwulan}/data/${this.penyebabKegagalan.kinerja_sub_kegiatan_id}/penyebab-kegagalan`)
          } else {
            this.$router.push('/kinerja-langkah-aksi')
          }
        })
      }).catch(() => {

        Swal.fire({
          type: 'error',
          title: 'Gagal simpan data!',
        })
      }).then(() => this.isBusy = false)
    },
    async getKinerjaSubKegiatan(satkerId) {
      this.isKinerjaSubKegiatanBusy = true

      const { data: {
        subKegiatan,
        sasaranStrategisRpjmd,
        sasaranStrategisPd,
        kinerjaProgram,
        kinerjaKegiatan,
        kinerjaSubKegiatan,
      }} = await axios.get('kinerja-langkah-aksi/create', {
        params: {
          satuan_kerja_id: satkerId
        }
      })

      this.subKegiatan = subKegiatan
      this.sasaranStrategisRpjmd = sasaranStrategisRpjmd
      this.sasaranStrategisPd = sasaranStrategisPd
      this.kinerjaProgram = kinerjaProgram
      this.kinerjaKegiatan = kinerjaKegiatan
      this.kinerjaSubKegiatan = kinerjaSubKegiatan

      this.isKinerjaSubKegiatanBusy = false
    },
  },
  watch: {
    'form.satuan_kerja_id': function (newVal) {
      if (!this.$role.isSuper() || this.isKinerjaSubKegiatanBusy) return false;

      // reset saat ubah satuan kerja
      this.form.sub_kegiatan_id = null

      this.getKinerjaSubKegiatan(newVal)
    },
    realisasiAnggaran: function (newVal) {
      this.form.realisasi_anggaran = newVal
    },
    capaianAnggaran: function (newVal) {
      this.form.capaian_anggaran = newVal
    },
  },
}
</script>