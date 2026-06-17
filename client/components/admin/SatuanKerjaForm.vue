<script>
export default {
  name: 'AdminSatuanKerjaForm',

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
    isEdit() {
      return this.mode === 'edit'
    },
    statusOptions() {
      return [
        { value: null, text: 'Pilih status' },
        { value: 1, text: 'Aktif' },
        { value: 0, text: 'Tidak Aktif' },
      ]
    },
  },
}
</script>

<template>
  <b-card>
    <form @submit.prevent="$emit('submit')">
      <b-row>
        <b-col cols="12" md="4">
          <b-form-group label="ID Satuan Kerja" label-class="font-weight-bold" label-for="satuan_kerja_id">
            <b-form-input id="satuan_kerja_id" v-model.number="form.satuan_kerja_id" type="number" min="1" required :readonly="isEdit"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Tahun" label-class="font-weight-bold" label-for="tahun_id">
            <b-form-input id="tahun_id" v-model.number="form.tahun_id" type="number" min="1900"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Status" label-class="font-weight-bold" label-for="status">
            <b-form-select id="status" v-model="form.status" :options="statusOptions"></b-form-select>
          </b-form-group>
        </b-col>
      </b-row>

      <b-form-group label="Nama OPD" label-class="font-weight-bold" label-for="satuan_kerja_nama">
        <b-form-input id="satuan_kerja_nama" v-model="form.satuan_kerja_nama" required></b-form-input>
      </b-form-group>

      <b-row>
        <b-col cols="12" md="4">
          <b-form-group label="Alias" label-class="font-weight-bold" label-for="satuan_kerja_nama_alias">
            <b-form-input id="satuan_kerja_nama_alias" v-model="form.satuan_kerja_nama_alias"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Kode" label-class="font-weight-bold" label-for="kode">
            <b-form-input id="kode" v-model="form.kode"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Kode SKPD" label-class="font-weight-bold" label-for="kode_skpd">
            <b-form-input id="kode_skpd" v-model="form.kode_skpd"></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-form-group label="Alamat" label-class="font-weight-bold" label-for="satuan_kerja_alamat">
        <b-form-textarea id="satuan_kerja_alamat" v-model="form.satuan_kerja_alamat" rows="2" max-rows="6"></b-form-textarea>
      </b-form-group>

      <b-row>
        <b-col cols="12" md="4">
          <b-form-group label="Kota" label-class="font-weight-bold" label-for="kota">
            <b-form-input id="kota" v-model="form.kota"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Kecamatan" label-class="font-weight-bold" label-for="kecamatan">
            <b-form-input id="kecamatan" v-model="form.kecamatan"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Kelurahan" label-class="font-weight-bold" label-for="kelurahan">
            <b-form-input id="kelurahan" v-model="form.kelurahan"></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-row>
        <b-col cols="12" md="4">
          <b-form-group label="Latitude" label-class="font-weight-bold" label-for="latitude">
            <b-form-input id="latitude" v-model.number="form.latitude" type="number" step="any"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Longitude" label-class="font-weight-bold" label-for="longitude">
            <b-form-input id="longitude" v-model.number="form.longitude" type="number" step="any"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Kecamatan ID" label-class="font-weight-bold" label-for="kecamatan_id">
            <b-form-input id="kecamatan_id" v-model.number="form.kecamatan_id" type="number"></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-button v-b-toggle:satuan-kerja-lainnya variant="outline-secondary" size="sm" type="button" class="mb-3">
        Field Tambahan
      </b-button>

      <b-collapse id="satuan-kerja-lainnya">
        <b-row>
          <b-col cols="12" md="4">
            <b-form-group label="Kel/Desa" label-class="font-weight-bold" label-for="satuan_kerja_kel_ds">
              <b-form-input id="satuan_kerja_kel_ds" v-model="form.satuan_kerja_kel_ds"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="4">
            <b-form-group label="Khusus" label-class="font-weight-bold" label-for="satuan_kerja_khusus">
              <b-form-input id="satuan_kerja_khusus" v-model="form.satuan_kerja_khusus"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="4">
            <b-form-group label="SAPK ID" label-class="font-weight-bold" label-for="sapk_id">
              <b-form-input id="sapk_id" v-model="form.sapk_id"></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row>
          <b-col cols="12" md="3">
            <b-form-group label="Bobot" label-class="font-weight-bold" label-for="bobot">
              <b-form-input id="bobot" v-model.number="form.bobot" type="number" step="any"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="3">
            <b-form-group label="Kab/Kota ID" label-class="font-weight-bold" label-for="m_kabkot_id">
              <b-form-input id="m_kabkot_id" v-model.number="form.m_kabkot_id" type="number"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="3">
            <b-form-group label="Rumpun ID" label-class="font-weight-bold" label-for="rumpun_id">
              <b-form-input id="rumpun_id" v-model.number="form.rumpun_id" type="number"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="3">
            <b-form-group label="Lampiran No" label-class="font-weight-bold" label-for="lampiran_no">
              <b-form-input id="lampiran_no" v-model.number="form.lampiran_no" type="number" step="any"></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row>
          <b-col cols="12" md="6">
            <b-form-group label="Create Username" label-class="font-weight-bold" label-for="create_username">
              <b-form-input id="create_username" v-model="form.create_username"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="6">
            <b-form-group label="Update Username" label-class="font-weight-bold" label-for="update_username">
              <b-form-input id="update_username" v-model="form.update_username"></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
      </b-collapse>

      <div class="text-right mt-3">
        <nuxt-link to="/admin/satuan-kerja" class="btn btn-outline-secondary mr-2">
          Batal
        </nuxt-link>
        <b-button variant="primary" :disabled="isBusy" type="submit">
          <i v-if="isBusy" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
          <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
          Simpan
        </b-button>
      </div>
    </form>
  </b-card>
</template>
