
<template>
  <b-card>
    <form @submit.prevent="store()">
      <OptionSatuanKerja v-if="!$role.isPerangkatDaerah()"
        v-model="form.satuan_kerja_id"
      />
      <div v-if="form.satuan_kerja_id">
        <b-form-group
          label-cols="12"
          label-cols-md="2"
          label="Rekomendasi LHE"
          label-class="font-weight-bold"
          label-for="rekomendasi"
        >
          <b-form-input id="rekomendasi" v-model="form.rekomendasi"></b-form-input>
        </b-form-group>
        <b-form-group  v-if="$role.isPerangkatDaerah()"
          label-cols="12"
          label-cols-md="2"
          label="Tindak Lanjut"
          label-class="font-weight-bold"
          label-for="tindak_lanjut"
        >
          <b-form-input id="tindak_lanjut" v-model="form.tindak_lanjut"></b-form-input>
        </b-form-group>
        <b-form-group  v-if="$role.isPerangkatDaerah()"
          label-cols="12"
          label-cols-md="2"
          label="Target"
          label-class="font-weight-bold"
          label-for="target"
        >
          <b-form-input id="target" v-model="form.target"></b-form-input>
        </b-form-group>
        <b-form-group  v-if="$role.isPerangkatDaerah()"
          label-cols="12"
          label-cols-md="2"
          label="Waktu"
          label-class="font-weight-bold"
          label-for="waktu"
        >
          <b-form-input id="waktu" v-model="form.waktu"></b-form-input>
        </b-form-group>
        <b-form-group  v-if="$role.isPerangkatDaerah()"
          label-cols="12"
          label-cols-md="2"
          label="Penanggung Jawab"
          label-class="font-weight-bold"
          label-for="penanggung_jawab"
        >
          <b-form-input id="penanggung_jawab" v-model="form.penanggung_jawab"></b-form-input>
        </b-form-group>
        <b-form-group v-if="$role.isPerangkatDaerah()" label-cols="12" label-cols-md="2" label="Status / Progres" label-class="font-weight-bold" label-for="status">
          <b-form-radio-group
            id="status"
            v-model="form.status"
            name="status"
            class="mb-2"
          >
            <b-form-radio value="Dalam Proses">Dalam Proses</b-form-radio>
            <b-form-radio value="Selesai">Selesai</b-form-radio>
          </b-form-radio-group>
        </b-form-group>
        <b-form-group  v-if="$role.isPerangkatDaerah()"
          label-cols="12"
          label-cols-md="2"
          label="Link Eviden"
          label-class="font-weight-bold"
          label-for="link_eviden"
        >
          <b-form-input id="link_eviden" v-model="form.link_eviden"></b-form-input>
        </b-form-group>
        <div class="text-right mt-5">
          <b-button variant="primary" :disabled="isBusy" type="submit">
            <i
              v-if="isBusy"
              class="fa fa-spinner fa-pulse"
              aria-hidden="true"
            ></i>
            <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
            Simpan
          </b-button>
        </div>
      </div>
    </form>
  </b-card>
</template>
  
  <script>
import axios from "axios";
import { mapGetters } from "vuex";
import { debounce } from "lodash";
import Swal from "sweetalert2";
import { arrayChunk2 } from '~/plugins/utils'

export default {
  middleware: [
    'auth'
  ],

  data() {
    let progress_penyelesaian_bulanan = {}
    this.$const.months.forEach(month => {
      progress_penyelesaian_bulanan[month[0]] = null
    });
    return {
      kegiatan: [],
      form: {
        satuan_kerja_id: null,
        rekomendasi: null,
        target: null,
        waktu: null,
        penanggung_jawab: null,
        status: null,
        link_eviden: null,
      },
      isBusy: false,
    };
  },
  created() {
    /** set default satuan kerja */
    this.form.satuan_kerja_id = this.user.satuan_kerja_id;
  },
  computed: {
    ...mapGetters({
      user: "auth/user",
    }),
  },

  methods: {
    arrayChunk2,
    store() {
      //const id = parseInt(params.id);
      this.isBusy = true;
      
      axios
        .post("lke-rekomendasi", this.form)
        .then(() => {
          Swal.fire({
            type: "success",
            title: "Berhasil simpan data",
          }).then(() => this.$router.push("/lke/rekomendasi"));
        })
        .catch(() => {
          Swal.fire({
            type: "error",
            title: "Gagal simpan data!",
          });
        })
        .then(() => (this.isBusy = false));
    },
  },
  watch: {
    "form.satuan_kerja_id": function (newVal) {
      
    },
  },
};
</script>