<script>
export default {
  name: 'AdminPegawaiForm',

  props: {
    form: {
      type: Object,
      required: true,
    },
    mode: {
      type: String,
      default: 'create',
    },
    isBusy: {
      type: Boolean,
      default: false,
    },
  },

  computed: {
    statusOptions() {
      return [
        { value: '1', text: 'Aktif' },
        { value: '0', text: 'Tidak Aktif' },
      ]
    },
  },
}
</script>

<template>
  <b-card>
    <form @submit.prevent="$emit('submit')">
      <OptionSatuanKerja
        v-if="$role.isSuper()"
        id="pegawai-satuan-kerja"
        v-model="form.id_satuan_kerja"
        label-title="Satuan Kerja / OPD"
      />

      <div v-if="form.id_satuan_kerja">
        <b-row>
          <b-col cols="12" md="6">
            <b-form-group label="NIP" label-class="font-weight-bold" label-for="peg_nip">
              <b-form-input id="peg_nip" v-model="form.peg_nip" required></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="6">
            <b-form-group label="Status" label-class="font-weight-bold" label-for="peg_status">
              <b-form-select id="peg_status" v-model="form.peg_status" :options="statusOptions" required></b-form-select>
            </b-form-group>
          </b-col>
        </b-row>

        <b-form-group label="Nama Pegawai" label-class="font-weight-bold" label-for="peg_nama">
          <b-form-input id="peg_nama" v-model="form.peg_nama" required></b-form-input>
        </b-form-group>

        <b-row>
          <b-col cols="12" md="6">
            <b-form-group label="Jabatan" label-class="font-weight-bold" label-for="jabatan_nama">
              <b-form-input id="jabatan_nama" v-model="form.jabatan_nama"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="6">
            <b-form-group label="Unit Kerja" label-class="font-weight-bold" label-for="unit_kerja_nama">
              <b-form-input id="unit_kerja_nama" v-model="form.unit_kerja_nama"></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <div class="text-right mt-3">
          <nuxt-link to="/admin/pegawai" class="btn btn-outline-secondary mr-2">
            Batal
          </nuxt-link>
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
