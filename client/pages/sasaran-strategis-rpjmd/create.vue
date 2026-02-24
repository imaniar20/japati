<template>
  <b-card>
    <form @submit.prevent="store()">
      <OptionSatuanKerja v-if="$role.isSuper()" v-model="form.satuan_kerja_id" />

      <OptionMisi v-model="form.misi_id" :ids="visiMisiRpjmd.map(el => el.misi_id)" />
      <OptionTujuan v-model="form.tujuan_id" :ids="visiMisiRpjmd.map(el => el.tujuan_id)" />
      <OptionIndikatorTujuan v-model="form.indikator_tujuan_id" :ids="visiMisiRpjmd.map(el => el.indikator_tujuan_id)" />

      <b-form-group label-class="font-weight-bold pt-0" label-cols="12" label-cols-md="2" label="Target RPJMD" label-for="target-rpjmd">
        <v-select id="target-rpjmd" 
          v-model="form.target_visi_misi_rpjmd_id" 
          :options="targetVisiMisiRpjmd" 
          :reduce="opt => opt.id" 
          placeholder="Pilih Target RPJMD"
          :getOptionLabel="labelTargetRpjmd"
        >
          <template #search="{attributes, events}">
            <input
              class="vs__search"
              :required="!form.target_visi_misi_rpjmd_id"
              v-bind="attributes"
              v-on="events"
            />
          </template>
        </v-select>
      </b-form-group>

      <OptionSasaranStrategis v-model="form.sasaran_strategis_id" />
      <OptionIndikatorSasaranStrategis v-model="form.indikator_sasaran_strategis_id" />

      <b-form-group label-cols="12" label-cols-md="2" label="Satuan" label-class="font-weight-bold" label-for="satuan">
        <b-form-input id="satuan" v-model="form.satuan" required></b-form-input>
      </b-form-group>
      <b-form-group label-cols="12" label-cols-md="2" label="Perbandingan realisasi dari tahun lalu" label-class="font-weight-bold" label-for="perbandingan_realisasi">
        <b-form-input id="perbandingan_realisasi" v-model="form[$helper.getKeyTahun('perbandingan_realisasi_tahun')]" required></b-form-input>
      </b-form-group>
      <b-form-group label-cols="12" label-cols-md="2" label="Perbandingan realisasi kinerja tahun ini dengan target RPJMD" label-class="font-weight-bold" label-for="perbandingan_realisasi_target">
        <b-form-input id="perbandingan_realisasi_target" v-model="form[$helper.getKeyTahun('perbandingan_realisasi_target')]"></b-form-input>
      </b-form-group>

      <b-row class="mt-4">
        <b-col sm md="4">
          <b-form-group :label="`Target kinerja tahun baseline ${form.tahun_mulai - 1}`" label-class="font-weight-bold"  label-for="target_baseline">
            <b-form-input id="target_baseline" v-model="form.target_baseline" required></b-form-input>
          </b-form-group>
        </b-col>
        <b-col sm md="4">
          <b-form-group :label="`Realisasi kinerja tahun baseline ${form.tahun_mulai - 1}`" label-class="font-weight-bold"  label-for="realisasi_baseline">
            <b-form-input id="realisasi_baseline" v-model="form.realisasi_baseline"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col sm md="4">
          <b-form-group :label="`Capaian kinerja tahun baseline ${form.tahun_mulai - 1} (%)`" label-class="font-weight-bold"  label-for="capaian_baseline">
            <b-form-input type="number" step="0.01" id="capaian_baseline" v-model="form.capaian_baseline"></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-row v-for="(kinerja, index) of kinerjaTahunList" :key="index">
        <b-col sm md="4">
          <b-form-group :label="`Target kinerja tahun ${form.tahun_mulai + index}`" label-class="font-weight-bold" :label-for="kinerja.target">
            <b-form-input :id="kinerja.target" v-model="form[kinerja.target]" required></b-form-input>
          </b-form-group>
        </b-col>
        <b-col sm md="4">
          <b-form-group :label="`Realisasi kinerja tahun ${form.tahun_mulai + index}`" label-class="font-weight-bold" :label-for="kinerja.realisasi">
            <b-form-input :id="kinerja.realisasi" v-model="form[kinerja.realisasi]"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col sm md="4">
          <b-form-group :label="`Capaian kinerja tahun ${form.tahun_mulai + index} (%)`" label-class="font-weight-bold" :label-for="kinerja.capaian">
            <b-form-input type="number" step="0.01" :id="kinerja.capaian" v-model="form[kinerja.capaian]"></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-form-group label-cols="12" label-cols-md="2" label="Rata-Rata Nasional" label-class="font-weight-bold" label-for="rata_nasional" class="mt-4">
        <b-form-input id="rata_nasional" type="number" min="0" step="0.00001" v-model="form.rata_nasional"></b-form-input>
      </b-form-group>
      <b-form-group label-cols="12" label-cols-md="2" label="Peringkat di Nasional" label-class="font-weight-bold" label-for="peringkat_nasional">
        <b-form-input id="peringkat_nasional" type="number" min="0" v-model="form.peringkat_nasional"></b-form-input>
      </b-form-group>
      <b-form-group label-cols="12" label-cols-md="2" label="Strategi" label-class="font-weight-bold" label-for="strategi">
        <b-form-textarea id="strategi" v-model="form.strategi"></b-form-textarea>
      </b-form-group>
      <b-form-group label-cols="12" label-cols-md="2" label="Raihan Penghargaan" label-class="font-weight-bold" label-for="penghargaan">
        <b-form-input id="penghargaan" v-model="form.penghargaan"></b-form-input>
      </b-form-group>

      <div class="text-right mt-5">
        <b-button variant="primary" :disabled="isBusy" type="submit">
          <i v-if="isBusy" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
          <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
          Simpan
        </b-button>
      </div>
    </form>
  </b-card>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { mapGetters } from 'vuex'

export default {
  middleware({ $role }) {
    // restrict biro, view-only
    const restrictBiro = !$role.isBiro();

    $role.hasRoles(`auth && ${restrictBiro} && (super_admin || pemerintah_daerah || setda)`)
  },
  async asyncData() {
    const { data: {
      visiMisiRpjmd,
    }} = await axios.get('sasaran-strategis-rpjmd/create')

    return {
      visiMisiRpjmd,
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
        capaian_baseline: null,
        capaian_1: null,
        capaian_2: null,
        capaian_3: null,
        capaian_4: null,
        capaian_5: null,
        rata_nasional: null,
        peringkat_nasional: null,
        strategi: null,
        misi_id: null,
        tujuan_id: null,
        indikator_tujuan_id: null,
        target_visi_misi_rpjmd_id: null,
        capaian_terhadap_target_akhir: null,
        penghargaan: null,
        perbandingan_realisasi_tahun_1: null,
        perbandingan_realisasi_tahun_2: null,
        perbandingan_realisasi_tahun_3: null,
        perbandingan_realisasi_tahun_4: null,
        perbandingan_realisasi_tahun_5: null,
        perbandingan_realisasi_target_1: null,
        perbandingan_realisasi_target_2: null,
        perbandingan_realisasi_target_3: null,
        perbandingan_realisasi_target_4: null,
        perbandingan_realisasi_target_5: null,
      },
      isBusy: false,
    }
  },
  created() {
    /** set default satuan kerja */
    this.form.satuan_kerja_id = this.user.satuan_kerja_id
  },
  computed: {
    ...mapGetters({
      user: 'auth/user'
    }),
    targetVisiMisiRpjmd() {
      if (this.form.indikator_tujuan_id === null) return [];

      return this.visiMisiRpjmd.filter(el => el.indikator_tujuan_id == this.form.indikator_tujuan_id)
    },
  },
  methods: {
    store() {
      this.isBusy = true

      axios.post('sasaran-strategis-rpjmd', this.form)
      .then(() => {
        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        }).then(() => this.$router.push('/sasaran-strategis-rpjmd'))
      }).catch(() => {

        Swal.fire({
          type: 'error',
          title: 'Gagal simpan data!',
        })
      }).then(() => this.isBusy = false)
    },
    labelTargetRpjmd(opt) {
      return opt[this.$helper.getKeyTahun('target')]
    },
  },
  watch: {
    'form.indikator_tujuan_id'() {
      this.form.target_visi_misi_rpjmd_id = null
    }
  }
}
</script>