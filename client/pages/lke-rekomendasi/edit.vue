
<template>
  <b-card>
    <form @submit.prevent="update()">

        <b-form-group  
          label-cols="12"
          label-cols-md="2"
          label="Rekomendasi"
          label-class="font-weight-bold"
          label-for="rekomendasi"
        >
          <b-form-input id="rekomendasi" :disabled="!$role.isValidatorLKE() && !$role.isValidatorPleno()" v-model="form.rekomendasi"></b-form-input>
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
        <b-form-group v-if="$role.isValidatorLKE()" label-cols="12" label-cols-md="2" label="Status Monev" label-class="font-weight-bold" label-for="status">
          <b-form-radio-group
            id="status_monev"
            v-model="form.status_monev"
            name="status_monev"
            class="mb-2"
          >
            <b-form-radio value="Tidak Sesuai">Tidak Sesuai</b-form-radio>
            <b-form-radio value="Sesuai">Sudah Sesuai</b-form-radio>
          </b-form-radio-group>
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
        <b-form-group v-if="$role.isPerangkatDaerah()" label-cols="12" label-cols-md="2" label="Status Tindak Lanjut" label-class="font-weight-bold" label-for="status">
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

  async asyncData({ params }) {
    const id = parseInt(params.id)
    const { data: {
     form
    }} = await axios.get(`lke-rekomendasi/${id}/edit`)

    return {
      id,
      form
    }
  },


  data() {
    return {
      program: [],
      isBusy: false,
    };
  },
  computed: {
    ...mapGetters({
      user: "auth/user",
    }),
  },

  methods: {
    arrayChunk2,
    async update() {
      try {
        this.isBusy = true
        console.log(this.form)
        await axios.patch(`lke-rekomendasi/${this.id}`, this.form)
        Swal.fire({
          type: 'success',
          title: 'Berhasil menyimpan data'
        }).then(() => this.$router.push("/lke/rekomendasi"));
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: 'Gagal menyimpan data!',
        })
      } finally {
        this.isBusy = false
      }
    },
  },
  watch: {
  },
};
</script>