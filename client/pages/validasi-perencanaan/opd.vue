<script>
import axios from 'axios'

export default {
  middleware: ['role-validator-perencanaan'],

  data() {
    return {
      isBusy: {
        getData: false,
      },
      data: [],
      table: {
        fields: [
          { key: 'satuan_kerja_nama', label: 'Satuan Kerja' },
          { key: 'action', label: 'Aksi' },
        ]
      }
    }
  },

  computed: {
    tahap: function() {
      if (this.$role.isValidatorPerencanaan1()) {
        return 1
      } else if (this.$role.isValidatorPerencanaan2()) {
        return 2
      } else if (this.$role.isValidatorPerencanaan3()) {
        return 3
      }
    }
  },

  mounted() {
    this.getData()
  },
  
  methods: {
    async getData() {
      this.isBusy.getData = true

      const { data } = await axios.get('validasi-perencanaan/validasi')

      this.data = data.data
      this.isBusy.getData = false
    }
  }
}
</script>

<template>
  <b-card>
    <!-- create b-table base on table fields -->
    <b-table responsive hover bordered :fields="table.fields" :items="data" :busy="isBusy.getData" show-empty head-variant="info">
      <template #cell(action)="{ item }">
        <div class="text-nowrap">
          <b-badge v-if="!item.validasi_perencanaan || item.validasi_perencanaan.tahap < tahap" variant="secondary">
            Belum submit validasi
          </b-badge>
          <template v-else-if="item.validasi_perencanaan.tahap == tahap">
            <template v-if="item.validasi_perencanaan.status === false">
              <b-badge variant="danger">
                Validasi ditolak
              </b-badge>
              <b-alert v-if="item.validasi_perencanaan.catatan" variant="danger" show class="mt-2">
                <b>Catatan:</b>
                <p style="white-space: pre">{{ item.validasi_perencanaan.catatan }}</p>
              </b-alert>
            </template>
            <b-badge v-else-if="item.validasi_perencanaan.status === true" variant="success">
              Validasi diterima
            </b-badge>
            <nuxt-link v-else :to="`/validasi-perencanaan/${item.satuan_kerja_id}`" class="btn btn-primary btn-sm m-1" title="Validasi">
              Validasi <i class="fa fa-angle-double-right" aria-hidden="true"></i>
            </nuxt-link>
          </template>
          <b-badge v-else variant="success">
            Validasi diterima
          </b-badge>
        </div>
      </template>
    </b-table>
  </b-card>
</template>