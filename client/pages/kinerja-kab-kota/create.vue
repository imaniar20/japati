<template>
  <b-card>
    <form @submit.prevent="store()">
      <OptionSatuanKerja v-if="$role.isSuper()" v-model="form.satuan_kerja_id" />
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
                :required="!form.kinerja_kegiatan_id"
                v-bind="attributes"
                v-on="events"
              />
            </template>
            <template #option="opt">
              <div class="border-bottom py-2 my-1">
                <div>Sasaran: <b>{{ opt.sasaran }}</b></div>
                <div>Kegiatan: <b>{{ opt.kegiatan?.nama || '-' }}</b></div>
                <div>Indikator kegiatan: <b>{{ opt.indikator }}</b></div>
                <div>Pagu anggaran: <b>{{ opt.anggaran | rupiah }}</b></div>
              </div>
            </template>
            <template #selected-option="opt">
              <div style="display: flex; align-items: baseline">
                <div>{{ opt.sasaran }} | {{ opt.kegiatan?.nama || '-' }} | {{ opt.indikator }}</div>
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
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                v-bind="attributes"
                v-on="events"
              />
            </template>
          </v-select>
        </b-form-group>
      <div v-if="form.satuan_kerja_id">
       <b-form-group label-cols="12" label-cols-md="2" label="Kinerja Kabupaten Kota" label-class="font-weight-bold" label-for="kinerja-kabkota">
          <v-select id="kinerja-kabkota" 
            v-model="form.kinerja_kabkota_id" 
            :options="kinerjaKabKota" 
            :reduce="opt => opt.id"
            label="kabupaten_kota" 
            placeholder="Pilih Kabupaten Kota"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                v-bind="attributes"
                v-on="events"
              />
            </template>
          </v-select>
        </b-form-group>
        <b-form-group  label-cols="12" label-cols-md="2" label="Pilih Satuan Kinerja Kabupaten Kota" label-class="font-weight-bold" label-for="kinerja-kabkota-satuan-kerja">
          <v-select id="kinerja-kabkota-satuan-kerja" 
            v-model="form.kinerja_kabkota_satuan_kerja_id" 
            :options="satuanKerjaKabupatenList" 
            :reduce="opt => opt.satuan_kerja_id"
            label="satuan_kerja_nama" 
            placeholder="Pilih Satuan Kerja Kabupaten Kota"
            :loading="isKinerjaKegiatanBusy"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                v-bind="attributes"
                v-on="events"
              />
            </template>
          </v-select>
        </b-form-group>
          <b-form-group  label-cols="12" label-cols-md="2" label="Pilih Kinerja Sub Kegiatan Kabupaten Kota" label-class="font-weight-bold" label-for="kinerja-sub-kegiatan-kabkota-satuan-kerja">
          <v-select id="kinerja-sub-kegiatan-kabkota-satuan-kerja" 
            v-model="form.kinerja_kabkota_kinerja_sub_kegiatan" 
            :options="kinerjaSubKegiatanKabKotaList" 
            :reduce="opt => opt.id"
            label="sasaran" 
            placeholder="Pilih Kinerja Sub Kegiatan Kabupaten Kota"
            :loading="isKinerjaKegiatanBusy"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                v-bind="attributes"
                v-on="events"
              />
            </template>
          </v-select>
        </b-form-group>
       
    <div v-if="showFormKinerja" :loading="isKinerjaKegiatanBusy">
        <b-form-group label-cols="12" label-cols-md="2"  label="Sasaran Sub Kegiatan" label-class="font-weight-bold" label-for="sasaran">
          <b-form-input id="sasaran" v-model="form.sasaran" disabled></b-form-input>
        </b-form-group>
        
        <b-form-group label-cols="12" label-cols-md="2" label="Indikator Sub Kegiatan" label-class="font-weight-bold" label-for="indikator">
          <b-form-input id="indikator" v-model="form.indikator" disabled required></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Indikator versi Kemendagri"  label-class="font-weight-bold" label-for="indikator_kemendagri">
          <v-select id="indikator_kemendagri" disabled
            v-if="indikatorList.length"
            v-model="form.indikator_kemendagri" 
            :options="indikatorList" 
            :reduce="opt => opt.indikator"
            label="indikator" 
            placeholder="Pilih indikator"
            
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                v-bind="attributes"
                v-on="events"
              />
            </template>
          </v-select>
          <b-form-input v-else id="indikator_kemendagri-manual" disabled v-model="form.indikator_kemendagri"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Rencana Aksi" label-class="font-weight-bold" label-for="rencana_aksi">
          <b-form-input id="rencana_aksi" disabled v-model="form.rencana_aksi"></b-form-input>
        </b-form-group>
        <!-- <b-form-group label-cols="12" label-cols-md="2" label="Apakah Kinerja Eksternal?" label-class="font-weight-bold" label-for="is_external">
          <b-form-radio-group
            id="is_external"
            v-model="form.is_external"
            name="is_external"
            class="mb-2"
          >
            <b-form-radio :value="true">Iya</b-form-radio>
            <b-form-radio :value="false">Bukan</b-form-radio>
          </b-form-radio-group>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Apakah Rencana Aksi IKU Gubernur?" label-class="font-weight-bold" label-for="is_rencana_aksi_gubernur">
          <b-form-radio-group
            id="is_rencana_aksi_gubernur"
            v-model="form.is_rencana_aksi_gubernur"
            name="is_rencana_aksi_gubernur"
            class="mb-2"
          >
            <b-form-radio :value="true">Iya</b-form-radio>
            <b-form-radio :value="false">Bukan</b-form-radio>
          </b-form-radio-group>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Pengampu" label-class="font-weight-bold" label-for="pengampu">
          <b-form-radio-group
            id="pengampu"
            v-model="form.pengampu"
            name="pengampu"
            class="mb-2"
          >
            <b-form-radio value="unit-kerja">Unit Kerja</b-form-radio>
            <b-form-radio value="tim-kerja">Tim Kerja</b-form-radio>
          </b-form-radio-group>

          <v-select id="vso"
            v-if="form.pengampu == 'unit-kerja'"
            v-model="form.v_struktur_organisasi_id"
            :options="vso"
            :reduce="opt => opt.id"
            label="unit_kerja_nama" 
            placeholder="Pilih unit kerja"
            :filterBy="$helper.vSelectFilterBy('nama', 'ketua.peg_nama')"
          >
          </v-select>

          <div v-if="form.pengampu == 'tim-kerja'">
            <v-select id="tim-kerja"
              v-model="form.tim_kerja_id"
              :options="timKerja"
              :reduce="opt => opt.id"
              label="nama" 
              placeholder="Pilih Tim Kerja"
            >
              <template #option="{ nama, ketua }">
                {{ nama }} - {{ ketua?.peg_nama }}
              </template>
              <template #selected-option="{ nama, ketua }">
                {{ nama }} - {{ ketua?.peg_nama }}
              </template>
            </v-select>

            <div class="mt-2 text-right">
              <b-button size="sm" variant="primary" @click="$nuxt.$emit('show-modal-tambah-tim-kerja', 'kinerja-sub-kegiatan')">
                <i class="ti-plus" aria-hidden="true"></i> Tambah Tim Kerja
              </b-button>
            </div>
          </div>
        </b-form-group> -->
        <b-form-group label-cols="12" label-cols-md="2" label="Satuan" label-class="font-weight-bold" label-for="satuan">
          <b-form-input id="satuan" disabled v-model="form.satuan" required></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Target Kinerja" label-class="font-weight-bold" label-for="target">
          <b-form-input id="target" disabled type="number" step="0.001" v-model="form.target" required></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Realisasi Kinerja" label-class="font-weight-bold" label-for="realisasi">
          <b-form-input id="realisasi" disabled type="number" step="0.001" v-model="form.realisasi" :readonly="isAutoFillRealisasi"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Capaian Kinerja (%)" label-class="font-weight-bold" label-for="capaian">
          <b-form-input type="number" disabled step="0.01" id="capaian" v-model="form.capaian" :readonly="isAutoFillRealisasi"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Pagu Anggaran" label-class="font-weight-bold" label-for="anggaran">
          <money class="form-control" disabled id="anggaran" v-model="form.anggaran" v-bind="money" required></money>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Realisasi Anggaran" label-class="font-weight-bold" label-for="realisasi_anggaran">
          <money class="form-control" disabled id="realisasi_anggaran" v-model="form.realisasi_anggaran" v-bind="money" required disabled></money>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Capaian Anggaran (%)" label-class="font-weight-bold" label-for="capaian_anggaran">
          <b-form-input type="number" disabled step="0.01" id="capaian_anggaran" v-model="form.capaian_anggaran" disabled></b-form-input>
        </b-form-group>
        
        <b-row class="mt-5">
          <b-col xs md="6" v-for="(arrMonth, index) of arrayChunk2($const.months, 6)" :key="index">
            <b-row v-for="(month, monthIndex) of arrMonth" :key="monthIndex">
              <b-col xs sm="6">
                <b-form-group :label="`Target Kinerja ${month[1]}`" label-class="font-weight-bold" :label-for="`target-${month[0]}`">
                  <b-form-input type="number" disabled step="0.001" :id="`target-${month[0]}`" v-model="form.target_bulanan[month[0]]" required></b-form-input>
                </b-form-group>
              </b-col>
              <b-col xs sm="6">
                <b-form-group :label="`Realisasi Kinerja ${month[1]}`" label-class="font-weight-bold" :label-for="`realisasi-${month[0]}`">
                  <b-form-input type="number" disabled step="0.001" :id="`realisasi-${month[0]}`" v-model="form.realisasi_bulanan[month[0]]"></b-form-input>
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
                  <money class="form-control" disabled :id="`anggaran-${month[0]}`" v-model="form.anggaran_bulanan[month[0]]"  v-bind="money"></money>
                </b-form-group>
              </b-col>
              <b-col xs sm="6">
                <b-form-group :label="`Realisasi Anggaran ${month[1]}`" label-class="font-weight-bold" :label-for="`realisasi-anggaran-${month[0]}`">
                  <money class="form-control" disabled :id="`realisasi-anggaran-${month[0]}`" v-model="form.realisasi_anggaran_bulanan[month[0]]" v-bind="money"></money>
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
      </div>
    </form>

    <TambahTimKerja :satuan-kerja-id="form.satuan_kerja_id" @submit="setTimKinerja" />
  </b-card>
</template>

<script>
import axios from 'axios'
import { mapGetters } from 'vuex'
import Swal from 'sweetalert2'
import { Money } from 'v-money'
import { arrayChunk2 } from '~/plugins/utils'
import TambahTimKerja from '~/components/modals/TambahTimKerja'
import { moneyFormat } from '~/utils/formater'

export default {
  middleware: ['auth', 'role-perangkat-daerah'],
  components: {
    Money,
    TambahTimKerja,
  },
  async asyncData() {
    const { data: {
      kegiatan,
      subKegiatan,
      sasaranStrategisRpjmd,
      kinerjaProgram,
      kinerjaKegiatan,
      sasaranStrategisPd,
      vso,
      timKerja,
      kinerjaKabKota
    }} = await axios.get('kinerja-sub-kegiatan-kab-kota/create')

    return {
      kegiatan,
      subKegiatan,
      sasaranStrategisRpjmd,
      kinerjaProgram,
      kinerjaKegiatan,
      sasaranStrategisPd,
      vso,
      timKerja,
      kinerjaKabKota
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
        kegiatan_id: null,
        sub_kegiatan_id: null,
        sasaran: null,
        indikator: null,
        satuan: null,
        target: null,
        realisasi: 0,
        target_bulanan,
        realisasi_bulanan,
        anggaran: 0,
        anggaran_bulanan,
        realisasi_anggaran_bulanan,
        has_inovasi: false,
        inovasi_uraian: null,
        inovasi_tujuan: null,
        inovasi_lampiran: [],
        capaian: null,
        realisasi_anggaran: 0,
        capaian_anggaran: null,
        sasaran_strategis_rpjmd_id: null,
        kinerja_program_id: null,
        kinerja_kegiatan_id: null,
        sasaran_strategis_pd_id: null,
        v_struktur_organisasi_id: null,
        indikator_kemendagri: null,
        rencana_aksi: null,
        is_external: false,
        is_rencana_aksi_gubernur: false,
        kinerja_kabkota_id : null,
        kinerja_kabkota_satuan_kerja_id : null,
      },
      showFormKinerja: false,
      isBusy: false,
      satuanKerjaKabupatenList: [],
      kinerjaSubKegiatanKabKotaList : [],
      kinerjaSubKegiatanKabKotaDetail:{},
      isKinerjaKegiatanBusy: false,
      money: moneyFormat,
      isLampiranBusy: false,
      isKinerjaKabkotaBusy: false,
      
    }
  },
  created() {
    this.form.satuan_kerja_id = this.user.satuan_kerja_id;
     if(this.$route.query.id){
      let id = parseInt(this.$route.query.id, 10); // Convert the query parameter to a number
      this.form.kinerja_kegiatan_id = id
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
    capaian: function () {
      return Math.round(((this.form.realisasi / this.form.target * 100) + Number.EPSILON) * 100) / 100
    },
    indikatorList: function () {
      if (!this.form.sub_kegiatan_id) {
        return []
      }

      const subKegiatan = this.subKegiatan.find(el => el.id == this.form.sub_kegiatan_id)

      if (!subKegiatan) {
        return []
      }

      return subKegiatan.indikator
    },
    isAutoFillRealisasi: function () {
      return this.$helper.getTahunKinerja() >= 2024
    },
  },
  methods: {
    async getDataByKinerjaKabkota(kinerjaKabkotaId) {
    this.isKinerjaKegiatanBusy = true

      if (!kinerjaKabkotaId) return;
      this.isKinerjaKegiatanBusy = true
      try {
        const { data } = await axios.get(`kinerja-kabkota/satuan-kerja/${kinerjaKabkotaId}`)
        if (data) {
          this.satuanKerjaKabupatenList = data
        }
      } catch (error) {
        console.error('Error fetching data:', error)
        Swal.fire({
          type: 'error',
          title: 'Gagal memuat data Satuan Kerja Kabupaten Kota!',
        })
      } finally {
        this.isKinerjaKegiatanBusy = false
      }
    },
    async getDataByKinerjaSubKegiatanKabkota(kinerjaKabkotaId) {
      if (!kinerjaKabkotaId) return;
      this.isKinerjaKegiatanBusy = true
      try {
        const { data } = await axios.get(`kinerja-kabkota/kinerja-sub-kegiatan/${this.form.kinerja_kabkota_id}/${this.form.kinerja_kabkota_satuan_kerja_id}`)
        if (data) {
          this.kinerjaSubKegiatanKabKotaList = data
        }
      } catch (error) {
        console.error('Error fetching data:', error)
        Swal.fire({
          type: 'error',
          title: 'Gagal memuat data!',
        })
      } finally {
        this.isKinerjaKegiatanBusy = false
      }
    },
    async getDataByKinerjaSubKegiatanDetailKabkota(kinerjaKabkotaId) {
      if (!kinerjaKabkotaId) return;
      this.isKinerjaKegiatanBusy = true
      try {
        const { data } = await axios.get(`kinerja-kabkota/kinerja-sub-kegiatan-detail/${this.form.kinerja_kabkota_id}/${this.form.kinerja_kabkota_kinerja_sub_kegiatan}`)
        if (data) {
          this.showFormKinerja = true;
          this.kinerjaSubKegiatanKabKotaDetail = data;
          this.form.sasaran = data.sasaran;
          this.form.indikator = data.indikator;
          this.form.satuan = data.satuan;
          this.form.target = data.target;
          this.form.target_bulanan = data.target_bulanan;
          this.form.anggaran = data.anggaran;
          this.form.anggaran_bulanan = data.anggaran_bulanan;
          this.form.has_inovasi = data.has_inovasi;
          this.form.inovasi_uraian = data.inovasi_uraian
          this.form.inovasi_tujuan = data.inovasi_tujuan;
          this.form.realisasi_bulanan = data.realisasi_bulanan;
          this.form.kab_kota_id = this.form.kinerja_kabkota_id;
          this.form.kab_kota_satuan_kerja_id = this.form.kinerja_kabkota_satuan_kerja_id;
          this.form.kab_kota_kinerja_sub_kegiatan_id = this.form.kinerja_kabkota_kinerja_sub_kegiatan;
          

          console.log(data);
        }
      } catch (error) {
        console.error('Error fetching data:', error)
        Swal.fire({
          type: 'error',
          title: 'Gagal memuat data!',
        })
      } finally {
        this.isKinerjaKegiatanBusy = false
      }
    },
    arrayChunk2,
    store() {
      this.isBusy = true

      axios.post('kinerja-sub-kegiatan-kab-kota', this.form)
      .then(() => {
        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        }).then(() =>  {
          if(this.$route.query.from == 'cascading' ){
            this.$router.push('/display-mikro/cascading')
          }
          else{
            this.$router.push('/kinerja-kab-kota')
          }
          
        })
      }).catch(() => {

        Swal.fire({
          type: 'error',
          title: 'Gagal simpan data!',
        })
      }).then(() => this.isBusy = false)
    },
    async getKinerjaKegiatan(satkerId) {
      this.isKinerjaKegiatanBusy = true

      const { data: {
        kegiatan,
        subKegiatan,
        sasaranStrategisRpjmd,
        kinerjaProgram,
        kinerjaKegiatan,
        sasaranStrategisPd,
        vso,
      }} = await axios.get('kinerja-sub-kegiatan/create', {
        params: {
          satuan_kerja_id: satkerId
        }
      })

      this.kegiatan = kegiatan
      this.subKegiatan = subKegiatan
      this.sasaranStrategisRpjmd = sasaranStrategisRpjmd
      this.kinerjaProgram = kinerjaProgram
      this.kinerjaKegiatan = kinerjaKegiatan
      this.sasaranStrategisPd = sasaranStrategisPd
      this.vso = vso

      this.isKinerjaKegiatanBusy = false
    },
    uploadLampiran(e) {
      const file = e.target.files[0]

      if (!file) {
        return alert('Pilih berkas terlebih dahulu!')
      }

      const fileSize = file.size / 1000

      if (fileSize > 2048) {
        alert('Ukuran berkas terlalu besar. Ukuran maksimal adalah 2MB!')

        return
      }

      this.isLampiranBusy = true
      const formData = new FormData();
      formData.append('file', e.target.files[0])

      axios.post('kinerja-sub-kegiatan/upload', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        }).then(({ data }) => {
          this.form.inovasi_lampiran.push(data)
        }).catch(() => {
          Swal.fire({
            title: 'Gagal upload berkas',
            type: 'error'
          })
        }).then(() => {
          this.isLampiranBusy = false
          this.$refs['inovasi-lampiran'].reset()
        })

    },
    removeLampiran(index) {
      const confirmed = confirm('Apakah Anda yakin akan menghapus berkas ini?')

      if (!confirmed) return false;

      this.form.inovasi_lampiran.splice(index, 1)
    },
    setTimKinerja(data) {
      this.timKerja.push(data)
      this.form.tim_kerja_id = data.id
    },
  },
  watch: {
    'form.kinerja_kabkota_id': function (newVal) {
      this.getDataByKinerjaKabkota(newVal)
    },
     'form.kinerja_kabkota_satuan_kerja_id': function (newVal) {
      this.getDataByKinerjaSubKegiatanKabkota(newVal)
    },
    'form.kinerja_kabkota_kinerja_sub_kegiatan': function (newVal) {
      this.getDataByKinerjaSubKegiatanDetailKabkota(newVal)
    },

    'form.satuan_kerja_id': function (newVal) {
      if (!this.$role.isSuper() || this.isKinerjaKegiatanBusy) return false;

      // reset saat ubah satuan kerja
      this.form.kegiatan_id = null
      this.form.sasaran_kegiatan = null
      this.form.kinerja_kabkota_id = null
      this.getKinerjaKegiatan(newVal)
    },
    'form.sub_kegiatan_id': function (newVal) {
      this.form.indikator_kemendagri = null

      if (!newVal) {
        this.form.anggaran = 0
        return
      }
      
      const subKegiatan = this.subKegiatan.find(el => el.id == newVal)
      
      this.form.anggaran = subKegiatan.anggaran
    },
    realisasiAnggaran: function (newVal) {
      this.form.realisasi_anggaran = newVal
    },
    capaianAnggaran: function (newVal) {
      this.form.capaian_anggaran = newVal
    },
    capaian: function (newVal) {
      this.form.capaian = newVal
    },
    'form.kinerja_kegiatan_id': function (newVal) {
      if (!newVal) {
        this.form.kegiatan_id = null
      } else {
        const kinerjaKegiatan = this.kinerjaKegiatan.find(_ => _.id == newVal)
        this.form.kegiatan_id = kinerjaKegiatan.kegiatan_id
        this.form.kinerja_program_id = kinerjaKegiatan.kinerja_program_id
        this.form.sasaran_strategis_pd_id = kinerjaKegiatan.kinerja_program.sasaran_strategis_pd_id
        this.form.sasaran_strategis_rpjmd_id = kinerjaKegiatan.kinerja_program.sasaran_strategis_pd.sasaran_strategis_rpjmd_id
      }
    },
    'form.realisasi_bulanan': {
      handler: function (newVal) {
        if (this.isAutoFillRealisasi) {
          this.form.realisasi = Object.values(newVal).reduce((acc, next) => acc + Number(next), 0)
        }
      },
      deep: true,
    },
  },
}
</script>