<script>
import axios from 'axios'
import ValidasiSkp from '~/components/ValidasiSkp.vue'

export default {
  middleware: 'auth',

  components: {
    ValidasiSkp,
  },

  data() {
    let satker = null

    if (this.$role.isSuper() || this.$role.isViewAll()) {
      satker = 1030
    } else if (this.$role.isSetda()) {
      satker = 100103010000
    }

    return {
      data: [],
      filter: {
        satuan_kerja_id: satker,
        tipe: '',
        status_validasi: null,
      },
      isBusy: {
        getData: false,
        setSkp: false,
      },
    }
  },

  computed: {
    filteredData() {
      return this.data.filter(_ => this.filter.tipe ? _.tipe == this.filter.tipe : true)
    }
  },

  watch: {
    'filter.satuan_kerja_id'() {
      this.getData()
    },
    'filter.status_validasi'() {
      this.getData()
    },
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData() {
      this.isBusy.getData = true

      const { data } = await axios.get('kamus-indikator', {
        params: this.filter
      })

      this.data = data
      this.isBusy.getData = false
    },
    async setSkp(id, set) {
      this.isBusy.setSkp = id

      await axios.post(
        set
        ? `validasi-skp/${id}/set`
        : `validasi-skp/${id}/unset`
      )

      this.isBusy.setSkp = false
      this.getData()
    },
  }
}
</script>

<template>
  <b-card>
    <div class="d-flex gap-5">
      <FilterSatuanKerja v-if="$role.isSuper() || $role.isSetda() || $role.isViewAll()" v-model="filter.satuan_kerja_id" :is-setda="$role.isSetda()" :selectProps="{clearable: false, appendToBody: true}" />
      <FilterStatusValidasi v-if="$role.isSuper()" v-model="filter.status_validasi" />
      <b-form-group label="Tampilkan Indikator" label-class="font-weight-bold">
        <b-form-select v-model="filter.tipe">
          <b-form-select-option value="">Semua</b-form-select-option>
          <b-form-select-option value="Indikator Sasaran Strategis RPJMD">Indikator Sasaran Strategis RPJMD</b-form-select-option>
          <b-form-select-option value="Indikator Sasaran Strategis Renstra PD">Indikator Sasaran Strategis Renstra PD</b-form-select-option>
          <b-form-select-option value="Indikator Program">Indikator Program</b-form-select-option>
          <b-form-select-option value="Indikator Kegiatan">Indikator Kegiatan</b-form-select-option>
          <b-form-select-option value="Indikator Sub Kegiatan">Indikator Sub Kegiatan</b-form-select-option>
        </b-form-select>
      </b-form-group>
    </div>

    <b-table-simple :aria-busy="isBusy.getData" responsive bordered hover>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th>No</b-th>
          <b-th>Indikator</b-th>
          <b-th>Nama Indikator Outcome/Output</b-th>
          <b-th>Nama Indikator SIPD/Kemendagri</b-th>
          <b-th>SKP</b-th>
          <b-th>Status Hasil Desk</b-th>
          <b-th>Catatan Hasil Desk</b-th>
          <b-th>Pembaharuan Terakhir</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr v-if="isBusy.getData">
          <b-td colspan="8" class="text-center"><b-spinner /></b-td>
        </b-tr>
        <b-tr v-else v-for="(item, index) in filteredData" :key="index" :variant="item.keterangan_perubahan ? 'warning' : null">
          <b-th>{{ index + 1 }}</b-th>
          <b-td>{{ item.tipe }}</b-td>
          <b-td>{{ item.indikator }}</b-td>
          <b-td>{{ item.indikator_kemendagri }}</b-td>
          <b-td>
            <div v-if="item.id" class="text-center">
              <b-badge v-if="item.skp && item.skp.is_skp" variant="success">Ya</b-badge>
              <b-badge v-else-if="item.skp && !item.skp.is_skp" variant="danger">Tidak</b-badge>
              <b-badge v-else variant="secondary">Belum di desk</b-badge>

              <div v-if="$role.isSuper() && item.skp" class="mt-2">
                <b-spinner small v-if="isBusy.setSkp == item.skp.id"></b-spinner>
                <div v-else>
                  <b-button v-if="!item.skp.is_skp" @click="setSkp(item.skp.id, true)" variant="success" size="sm" class="m-1 rounded-circle"  title="Tandai sebagai SKP">
                    <i class="fa fa-check" aria-hidden="true"></i>
                  </b-button>
                  <b-button v-else @click="setSkp(item.skp.id, false)" variant="danger" size="sm" class="m-1 rounded-circle"  title="Hapus tanda SKP">
                    <i class="fa fa-times" aria-hidden="true"></i>
                  </b-button>
                </div>
              </div>
            </div>
          </b-td>
          <b-td>
            <div v-if="item.id" class="text-center">
              <b-badge v-if="item.skp" variant="success">Sudah di desk</b-badge>
              <b-badge v-else-if="!item.skp" variant="secondary">Belum di desk</b-badge>

              <div v-if="$role.isSuper()" class="mt-2">
                <b-button @click="$refs.validasiSkp.validate(item.class, item.id, item.skp)" :variant="item.skp ? 'success' : 'outline-primary'" size="sm" class="m-1 rounded-circle"  :title="item.skp ? 'Desk Ulang' : 'Desk'">
                  <i class="fa fa-check" aria-hidden="true"></i>
                </b-button>
                <b-button v-if="!item.skp" @click="$refs.validasiSkp.reject(item.class, item.id)" variant="danger" size="sm" class="m-1 rounded-circle"  title="Data tidak sesuai">
                  <i class="fa fa-times" aria-hidden="true"></i>
                </b-button>
              </div>

              <div>{{ item.keterangan_perubahan }}</div>
            </div>
          </b-td>
          <b-td>
            <div v-if="item.id && !item.skp" style="white-space: break-spaces;">{{ item.keterangan }}</div>
            <div v-if="item.id && item.skp" style="white-space: break-spaces;">{{ item.catatan_validasi }}</div>
          </b-td>
          <b-td>
            <div v-if="item.skp" class="mb-2" style="white-space: nowrap;">Di desk pada: <br>{{ item.skp.updated_at | formatDateTime }}</div>
            <div style="white-space: nowrap;">Pembaharuan data terakhir: <br>{{ item.updated_at | formatDateTime }}</div>
          </b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>

    <ValidasiSkp ref="validasiSkp" @success="getData()" />
  </b-card>
</template>