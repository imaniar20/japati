<template>
  <b-card>
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

        <OptionSasaranStrategis :key="`sasaran-strategis-${keyComponent}`" v-model="form.sasaran_strategis_id" :ids="sasaranStrategisRpjmd.map(el => el.sasaran_strategis_id)" />
        <OptionIndikatorSasaranStrategis :key="`indikator-sasaran-strategis-${keyComponent}`" v-model="form.indikator_sasaran_strategis_id" :ids="sasaranStrategisRpjmd.map(el => el.indikator_sasaran_strategis_id)" />
        
        <b-form-group label-class="font-weight-bold pt-0" label-cols="12" label-cols-md="2" label="Kinerja Cross-Cutting" label-for="kinerja-bayangan">
          <v-select id="kinerja-bayangan" 
            v-model="form.kinerja_bayangan_id" 
            :options="kinerjaBayangan" 
            :reduce="opt => opt.id" 
            placeholder="Pilih Kinerja Cross-Cutting"
            :filterBy="$helper.vSelectFilterBy('sasaran', 'indikator')"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                v-bind="attributes"
                v-on="events"
              />
            </template>

            <template #option="item">
              <div><b>Sasaran:</b> {{ item.sasaran }}</div>
              <div><b>Indikator:</b> {{ item.indikator }}</div>
              <hr>
            </template>
            <template #selected-option="item">
              <div style="display: flex; align-items: baseline">
                {{ item.sasaran }} - {{ item.indikator }}
              </div>
            </template>
          </v-select>
        </b-form-group>
        
        <b-form-group label-cols="12" label-cols-md="2" label="Sasaran Strategis PD" label-class="font-weight-bold"  label-for="sasaran-strategis-satker">
          <b-form-input id="sasaran-strategis-satker" v-model="form.sasaran_strategis_satker"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="IKU PD" label-class="font-weight-bold" label-for="iku">
          <b-form-input id="iku" v-model="form.iku" required></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Satuan" label-class="font-weight-bold" label-for="satuan">
          <b-form-input id="satuan" v-model="form.satuan" required></b-form-input>
        </b-form-group>
         <b-form-group label-cols="12" label-cols-md="2" label="Why (Penjelasan Kenapa Sasaran Startegis PD ditetapkan)" label-class="font-weight-bold" label-for="why">
          <b-form-input id="why" v-model="form.why"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Apakah masuk rapor kinerja?" label-class="font-weight-bold" label-for="is_rapor_kinerja">
          <b-form-radio-group
            id="is_rapor_kinerja"
            v-model="form.is_rapor_kinerja"
            name="is_rapor_kinerja"
            class="mb-2"
          >
            <b-form-radio :value="true">Iya</b-form-radio>
            <b-form-radio :value="false">Tidak</b-form-radio>
          </b-form-radio-group>
        </b-form-group>
        <b-card title="Definisi Operasional">
          <b-form-group
            label-cols="12"
            label-cols-md="2"
            label="Narasi"
            label-class="font-weight-bold"
            label-for="narasi"
          >
            <b-form-input
              id="target"
              v-model="form.do_narasi"
              placeholder="Narasi Definisi Operasional"
            ></b-form-input>
          </b-form-group>
          <b-form-group
            label-cols="12"
            label-cols-md="2"
            label="Rumus"
            label-class="font-weight-bold"
            label-for="rumus"
          >
            <b-form-file
              :id="`definisi-operasional-img`"
              :ref="`definisi-operasional-img`"
              @change="validateUploadDefinisiOperasional($event)"
              placeholder="Pilih Gambar"
              accept=".jpg, .png"
            ></b-form-file>
          </b-form-group>

          <b-form-group
            label-cols="12"
            label-cols-md="2"
            label="Keterangan"
            label-class="font-weight-bold"
            label-for="keterangan"
          >
            <b-form-textarea
              id="keterangan"
              v-model="form.do_keterangan"
              placeholder="Keterangan Definisi Operasional"
            ></b-form-textarea>
          </b-form-group>

          <b-form-group
            label-cols="12"
            label-cols-md="2"
            label="Sumber"
            label-class="font-weight-bold"
            label-for="sumber"
           
          >
            <b-form-input
              id="sumber"
              v-model="form.do_sumber"
               placeholder="Sumber Data Definisi Operasional"
            ></b-form-input>
          </b-form-group>
          
        </b-card>


        <b-row class="mt-4">
          <b-col sm md="4">
            <b-form-group :label="`Target kinerja tahun baseline ${form.tahun_mulai - 1}`" label-class="font-weight-bold"  label-for="target_baseline">
              <b-form-input type="number" step="0.00001" id="target_baseline" v-model="form.target_baseline" required></b-form-input>
            </b-form-group>
          </b-col>
          <b-col sm md="4">
            <b-form-group :label="`Realisasi kinerja tahun baseline ${form.tahun_mulai - 1}`" label-class="font-weight-bold"  label-for="realisasi_baseline">
              <b-form-input type="number" step="0.00001" id="realisasi_baseline" v-model="form.realisasi_baseline"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col sm md="4">
            <b-form-group :label="`Capaian kinerja tahun baseline ${form.tahun_mulai - 1} (%)`" label-class="font-weight-bold"  label-for="capaian_baseline">
              <b-form-input type="number" step="0.01" id="capaian_baseline" v-model="form.capaian_baseline" disabled></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row v-for="(kinerja, index) of kinerjaTahunList" :key="index">
          <b-col sm md="4">
            <b-form-group :label="`Target kinerja tahun ${form.tahun_mulai + index}`" label-class="font-weight-bold" :label-for="kinerja.target">
              <b-form-input type="number" step="0.00001" :id="kinerja.target" v-model="form[kinerja.target]" required></b-form-input>
            </b-form-group>
          </b-col>
          <b-col sm md="4">
            <b-form-group :label="`Realisasi kinerja tahun ${form.tahun_mulai + index}`" label-class="font-weight-bold" :label-for="kinerja.realisasi">
              <b-form-input type="number" step="0.00001" :id="kinerja.realisasi" v-model="form[kinerja.realisasi]" disabled></b-form-input>
            </b-form-group>
          </b-col>
          <b-col sm md="4">
            <b-form-group :label="`Capaian kinerja tahun ${form.tahun_mulai + index} (%)`" label-class="font-weight-bold" :label-for="kinerja.capaian">
              <b-form-input type="number" step="0.01" :id="kinerja.capaian" v-model="form[kinerja.capaian]" disabled></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <b-form-group label-cols="12" label-cols-md="2" label="Rata-Rata Realisasi Nasional" label-class="font-weight-bold" label-for="rata_nasional">
          <b-form-input id="rata_nasional" type="number" min="0" step="0.00001" v-model="form.rata_nasional"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Peringkat di Nasional" label-class="font-weight-bold" label-for="peringkat_nasional">
          <b-form-input id="peringkat_nasional" type="number" min="0" v-model="form.peringkat_nasional"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Rata-Rata Realisasi Internasional" label-class="font-weight-bold" label-for="rata_internasional">
          <b-form-input id="rata_internasional" type="number" min="0" step="0.00001" v-model="form.rata_internasional"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Peringkat di Internasional" label-class="font-weight-bold" label-for="peringkat_internasional">
          <b-form-input id="peringkat_internasional" type="number" min="0" v-model="form.peringkat_internasional"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Narasi Keberhasilan/Narasi Ketidakberhasilan" label-class="font-weight-bold" label-for="narasi_keberhasilan">
          <b-form-textarea id="narasi_keberhasilan" v-model="form.narasi_keberhasilan" rows="3"></b-form-textarea>
        </b-form-group>
        <h5 class="mb-3">Penghargaan Tahun Berjalan</h5>
        <b-form-group label-cols="12" label-cols-md="2" label="Redaksi" label-class="font-weight-bold" label-for="redaksi">
          <b-form-textarea id="redaksi" v-model="form.redaksi" rows="3"></b-form-textarea>
        </b-form-group>

        <b-form-group label-cols="12" label-cols-md="2" label="Lampiran" label-class="font-weight-bold" label-for="lampiran">
          <b-form-file ref="lampiran" placeholder="Pilih foto" @change="uploadLampiran($event)" accept=".jpg, .jpeg, .png" :disabled="isLampiranBusy"></b-form-file>
          <small v-if="isLampiranBusy" class="text-primary">Mengunggah berkas...</small>
          <small v-else>Maksimal ukuran foto 2MB</small>
          
          <div class="mt-4">
            <b-row v-for="(images, index) of arrayChunk2(form.lampiran, 6)" :key="index">
              <b-col v-for="(image, indexImage) of images" :key="indexImage" cols="6" sm="4" md="2" class="text-center mb-3">
                <b-card :img-src="image" :img-alt="image.substring(image.lastIndexOf('/') + 1)" img-top>
                  <div class="d-flex justify-content-center">
                    <a :href="image" class="btn btn-sm btn-success" target="_blank" title="Lihat"><i class="fa fa-eye" aria-hidden="true"></i></a>
                    <b-btn @click="removeLampiran(indexImage)" size="sm" variant="danger" class="mx-1" title="Hapus"><i class="fa fa-trash" aria-hidden="true"></i></b-btn>
                  </div>
                </b-card>
              </b-col>
            </b-row>
          </div>
        </b-form-group>

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
import { arrayChunk2 } from '~/plugins/utils'

export default {
  middleware({ $role }) {
    // restrict biro, view-only
    const restrictBiro = !$role.isBiro();

    $role.hasRoles(`auth && ${restrictBiro} && (super_admin || perangkat_daerah || setda)`)
  },
  async asyncData() {
    const { data: {sasaranStrategisRpjmd, kinerjaBayangan}} = await axios.get('sasaran-strategis-pd/create')

    return {
      sasaranStrategisRpjmd,
      kinerjaBayangan,
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
      form: {
        satuan_kerja_id: null,
        sasaran_strategis_id: null,
        indikator_sasaran_strategis_id: null,
        kinerja_bayangan_id: null,
        sasaran_strategis_satker: null,
        iku: null,
        satuan: null,
        tahun_mulai: this.$helper.getTahunMulai(),
        target_baseline: null,
        target_1: null,
        target_2: null,
        target_3: null,
        target_4: null,
        target_5: null,
        realisasi_baseline: null,
        realisasi_1: null,
        realisasi_2: null,
        realisasi_3: null,
        realisasi_4: null,
        realisasi_5: null,
        rata_nasional: null,
        peringkat_nasional: null,
        rata_internasional: null,
        peringkat_internasional: null,
        redaksi: null,
        lampiran: [],
        sasaran_strategis_rpjmd_id: null,
        narasi_keberhasilan: null,
        is_rapor_kinerja: true,
        do_narasi: null,
        do_rumus: "",
        do_keterangan: null,
        do_sumber: null,
        why:""
      },
      isBusy: false,
      isSasaranBusy: false,
      isLampiranBusy: false,
      keyComponent: 0,
    }
  },
  created() {
    /** set default satuan kerja */
    this.form.satuan_kerja_id = this.user.satuan_kerja_id;
     if(this.$route.query.id){
      let id = parseInt(this.$route.query.id, 10); // Convert the query parameter to a number
      this.form.sasaran_strategis_id = id
      let satker_rpjmd_id = parseInt(this.$route.query.satker_rpjmd_id, 10); // Convert the query parameter to a number
      this.form.sasaran_strategis_rpjmd_id = satker_rpjmd_id
       let indikator_id = parseInt(this.$route.query.indikator_id, 10); // Convert the query parameter to a number
      this.form.indikator_sasaran_strategis_id = indikator_id
    }
  },
  computed: {
    ...mapGetters({
      user: 'auth/user'
    }),
    capaianBaseline: function () {
      return Math.round(((this.form.realisasi_baseline / this.form.target_baseline * 100) + Number.EPSILON) * 100) / 100
    },
    capaian1: function () {
      return Math.round(((this.form.realisasi_1 / this.form.target_1 * 100) + Number.EPSILON) * 100) / 100
    },
    capaian2: function () {
      return Math.round(((this.form.realisasi_2 / this.form.target_2 * 100) + Number.EPSILON) * 100) / 100
    },
    capaian3: function () {
      return Math.round(((this.form.realisasi_3 / this.form.target_3 * 100) + Number.EPSILON) * 100) / 100
    },
    capaian4: function () {
      return Math.round(((this.form.realisasi_4 / this.form.target_4 * 100) + Number.EPSILON) * 100) / 100
    },
    capaian5: function () {
      return Math.round(((this.form.realisasi_5 / this.form.target_5 * 100) + Number.EPSILON) * 100) / 100
    },
  },
  methods: {
    arrayChunk2,
    async store() {
      this.isBusy = true

      if (!(await this.uploadDefinisiOperasional())) {
          return;
        }

      axios.post('sasaran-strategis-pd', this.form)
      .then(() => {
        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        }).then(() =>  {
          if(this.$route.query.from == 'cascading' ){
            this.$router.push('/display-mikro/cascading')
          }
          else{
            this.$router.push('/sasaran-strategis-pd')
          }
          
        })
      }).catch(() => {

        Swal.fire({
          type: 'error',
          title: 'Gagal simpan data!',
        })
      }).then(() => this.isBusy = false)
    },
    async getSasaranStrategis(satkerId) {
      this.isSasaranBusy = true

      const { data } = await axios.get('sasaran-strategis-pd/create', {
        params: {
          satuan_kerja_id: satkerId
        }
      })

      this.sasaranStrategisRpjmd = data.sasaranStrategisRpjmd
      this.keyComponent++

      this.isSasaranBusy = false
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

      axios.post('sasaran-strategis-pd/upload', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        }).then(({ data }) => {
          this.form.lampiran.push(data)
        }).catch(() => {
          Swal.fire({
            title: 'Gagal upload berkas',
            type: 'error'
          })
        }).then(() => {
          this.isLampiranBusy = false
          this.$refs.lampiran.reset()
        })

    },
    removeLampiran(index) {
      const confirmed = confirm('Apakah Anda yakin akan menghapus berkas ini?')

      if (!confirmed) return false;

      this.form.lampiran.splice(index, 1)
    },
    resetDefinisiOperasional() {
      this.$refs[`definisi-operasional-img`].reset();
    },
    validateUploadDefinisiOperasional(e) {
      const file = e.target.files[0];

      if (!file) {
        return alert("Pilih berkas terlebih dahulu!");
      }

      const fileSize = file.size / 1000;

      if (fileSize > 2048) {
        this.resetDefinisiOperasional();
        alert("Ukuran berkas terlalu besar. Ukuran maksimal adalah 2MB!");

        return;
      }
    },
    async uploadDefinisiOperasional() {
      const file = this.$refs[`definisi-operasional-img`].files[0];
      if(file){
        try {
        

        const formData = new FormData();
        formData.append("file", file);

        const { data } = await axios.post(
          "sasaran-strategis-pd/upload-definisi-operasional",
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
        );

        this.form.do_rumus = data;
        this.resetDefinisiOperasional();

        return true;
      } catch (error) {
        console.log(error)
        Swal.fire({
          title: "Gagal upload Gambar DO",
          type: "error",
        });

        return false;
      }
    }
      else{
        return true
      }
     
    
    },
  },
  watch: {
    'form.satuan_kerja_id': function (newVal) {
      if (!this.$role.isSuper() || this.isSasaranBusy) return false;

      // reset saat ubah satuan kerja
      this.form.sasaran_strategis_rpjmd_id = null
      this.form.sasaran_strategis_id = null
      this.form.indikator_sasaran_strategis_id = null

      this.getSasaranStrategis(newVal)
    },
    capaianBaseline: function (newVal) {
      this.form.capaian_baseline = newVal
    },
    capaian1: function (newVal) {
      this.form.capaian_1 = newVal
    },
    capaian2: function (newVal) {
      this.form.capaian_2 = newVal
    },
    capaian3: function (newVal) {
      this.form.capaian_3 = newVal
    },
    capaian4: function (newVal) {
      this.form.capaian_4 = newVal
    },
    capaian5: function (newVal) {
      this.form.capaian_5 = newVal
    },
  }
}
</script>