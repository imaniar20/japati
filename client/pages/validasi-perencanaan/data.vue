<script>
import Swal from 'sweetalert2'
import axios from 'axios'

export default {
  middleware: ['role-validator-perencanaan'],

  async asyncData({ params, redirect, $role }) {
    const satkerId = params.satkerId
    const { data: { data } } = await axios.get(`validasi-perencanaan/validasi/${satkerId}`)

    const tahapUser = () => {
      if ($role.isValidatorPerencanaan1()) {
        return 1
      } else if ($role.isValidatorPerencanaan2()) {
        return 2
      } else if ($role.isValidatorPerencanaan3()) {
        return 3
      }
      
      return null
    }

    if (!data || data.status !== null || data.tahap !== tahapUser()) {
      return redirect('/validasi-perencanaan')
    }

    return {
      validasiPerencanaan: data,
      satkerId,
    }
  },

  data() {
    return {
      satkerId: this.$route.params.satkerId,
      isBusy: {
        getData: false,
        validasi: false,
      },
      data: {
        data: [],
        current_page: 1,
        from: 1,
        total: 0,
        per_page: 50,
      },
      form: {
        status: null,
        catatan: null,
      },
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
    },
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData(page = 1) {
      this.isBusy.getData = true

      const { data } = await axios.get(`validasi-perencanaan/validasi/${this.satkerId}/data`, {
        params: {
          page,
        }
      })

      this.data = data.data
      this.isBusy.getData = false
    },
    async validasi() {
      this.isBusy.validasi = this.form.status ? 'terima' : 'tolak'

      await axios.post(`validasi-perencanaan/validasi/${this.satkerId}`, this.form)

      this.$router.push('/validasi-perencanaan')
    },
    async terima() {
      Swal.fire({
        title: 'Terima Validasi',
        text: 'Apakah Anda yakin akan terima validasi data?',
        type: 'question',
        showCancelButton: true,
        cancelButtonText: 'Batal',
        confirmButtonText: 'Terima'
      })
      .then(({ value }) => {
        if (value) {
          this.form.status = true
          this.form.catatan = null

          this.validasi()
        }
      })
    },
    async confirmTolak() {
      Swal.fire({
        title: 'Tolak Validasi',
        text: 'Tulis catatan tolak validasi',
        type: 'warning',
        input: 'textarea',
        inputPlaceholder: 'Tulis catatan',
        showCancelButton: true,
        cancelButtonText: 'Batal',
        confirmButtonText: 'Tolak',
        focusCancel: true,
      })
      .then(({ value }) => {
        this.form.status = false
        this.form.catatan = value

        this.validasi()
      })
    }
  }
}
</script>

<template>
  <b-card>
    <div class="text-right">
      <NuxtLink :to="`/public-display/display-mikro/cascading?satuan_kerja_id=${satkerId}`" class="btn btn-outline-primary">
        Cascading
      </NuxtLink>
      <b-button variant="success" @click="terima" :disabled="Boolean(isBusy.validasi)">
        <b-spinner small v-if="isBusy.validasi == 'terima'"></b-spinner>
        <i v-else class="fa fa-check" aria-hidden="true"></i>
        Terima
      </b-button>
      <b-button variant="danger" @click="confirmTolak" :disabled="Boolean(isBusy.validasi)">
        <b-spinner small v-if="isBusy.validasi == 'tolak'"></b-spinner>
        <i v-else class="fa fa-times" aria-hidden="true"></i>
        Tolak
      </b-button>
    </div>

    <b-table-simple :aria-busy="isBusy.getData" striped hover responsive bordered class="mt-3">
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle">No</b-th>
          <b-th class="align-middle">Sasaran Strategis RPJMD</b-th>
          <b-th class="align-middle">Indikator Sasaran Strategis RPJMD</b-th>

          <b-th class="align-middle">Sasaran Strategis Renstra PD</b-th>
          <b-th class="align-middle">Indikator Sasaran Strategis Renstra PD</b-th>
          
          <b-th class="align-middle">Sasaran Program</b-th>
          <b-th class="align-middle">Indikator Program</b-th>
          <b-th class="align-middle">Nama Program</b-th>
          <b-th v-if="tahap == 2" class="align-middle">Anggaran Program</b-th>
          <b-th v-if="tahap == 3" class="align-middle">Pengampu Program</b-th>

          <b-th class="align-middle">Sasaran Kegiatan</b-th>
          <b-th class="align-middle">Indikator Kegiatan</b-th>
          <b-th class="align-middle">Nama Kegiatan</b-th>
          <b-th v-if="tahap == 2" class="align-middle">Anggaran Kegiatan</b-th>
          <b-th v-if="tahap == 3" class="align-middle">Pengampu Kegiatan</b-th>

          <b-th class="align-middle">Sasaran Sub Kegiatan</b-th>
          <b-th class="align-middle">Indikator Sub Kegiatan</b-th>
          <b-th class="align-middle">Nama Sub Kegiatan</b-th>
          <b-th v-if="tahap == 2" class="align-middle">Anggaran Sub Kegiatan</b-th>
          <b-th v-if="tahap == 3" class="align-middle">Pengampu Sub Kegiatan</b-th>
        </b-tr>
      </b-thead>

      <b-tbody>
        <b-tr class="text-center" v-if="!data.data.length">
          <b-td colspan="14">There are no records to show</b-td>
        </b-tr>
        <b-tr v-else v-for="(item, index) of data.data" :key="item.id">
          <b-td><b>{{ data.from + index }}</b></b-td>

          <b-td>{{ item.kinerja_kegiatan?.kinerja_program?.sasaran_strategis_pd?.sasaran_strategis_rpjmd?.sasaran_strategis?.sasaran || '-' }}</b-td>
          <b-td>{{ item.kinerja_kegiatan?.kinerja_program?.sasaran_strategis_pd?.sasaran_strategis_rpjmd?.indikator_sasaran_strategis?.indikator || '-' }}</b-td>
          
          <b-td>{{ item.kinerja_kegiatan?.kinerja_program?.sasaran_strategis_pd?.sasaran_strategis_satker || '-' }}</b-td>
          <b-td>{{ item.kinerja_kegiatan?.kinerja_program?.sasaran_strategis_pd?.iku || '-' }}</b-td>
          
          <b-td>{{ item.kinerja_kegiatan?.kinerja_program?.sasaran || '-' }}</b-td>
          <b-td>{{ item.kinerja_kegiatan?.kinerja_program?.indikator || '-' }}</b-td>
          <b-td>{{ item.kinerja_kegiatan?.kinerja_program?.program?.nama || '-' }}</b-td>
          <b-td v-if="tahap == 2">{{ item.kinerja_kegiatan?.kinerja_program?.anggaran || 0 | rupiah }}</b-td>
          <b-td v-if="tahap == 3">
            <span v-if="item.kinerja_kegiatan?.kinerja_program?.pengampu == 'unit-kerja'">
              {{ item.kinerja_kegiatan?.kinerja_program?.struktur_organisasi?.jabatan_nama || '-' }}
            </span>
            <span v-else-if="item.kinerja_kegiatan?.kinerja_program?.pengampu == 'tim-kerja' && item.kinerja_kegiatan?.kinerja_program?.tim_kerja">
              {{ item.kinerja_kegiatan?.kinerja_program?.tim_kerja.nama }} - {{ item.kinerja_kegiatan?.kinerja_program?.tim_kerja.ketua?.peg_nama }}
            </span>
            <span v-else>-</span>
          </b-td>

          <b-td>{{ item.kinerja_kegiatan?.sasaran || '-' }}</b-td>
          <b-td>{{ item.kinerja_kegiatan?.indikator || '-' }}</b-td>
          <b-td>{{ item.kinerja_kegiatan?.kegiatan?.nama || '-' }}</b-td>
          <b-td v-if="tahap == 2">{{ item.kinerja_kegiatan?.anggaran || 0 | rupiah }}</b-td>
          <b-td v-if="tahap == 3">
            <span v-if="item.kinerja_kegiatan?.pengampu == 'unit-kerja'">
              {{ item.kinerja_kegiatan?.struktur_organisasi?.jabatan_nama || '-' }}
            </span>
            <span v-else-if="item.kinerja_kegiatan?.pengampu == 'tim-kerja' && item.kinerja_kegiatan?.tim_kerja">
              {{ item.kinerja_kegiatan?.tim_kerja?.nama }} - {{ item.kinerja_kegiatan?.tim_kerja?.ketua?.peg_nama }}
            </span>
            <span v-else>-</span>
          </b-td>

          <b-td>{{ item.sasaran }}</b-td>
          <b-td>{{ item.indikator }}</b-td>
          <b-td>{{ item.sub_kegiatan?.nama || '-' }}</b-td>
          <b-td v-if="tahap == 2">{{ item.anggaran | rupiah }}</b-td>
          <b-td v-if="tahap == 3">
            <span v-if="item.pengampu == 'unit-kerja'">
              {{ item.struktur_organisasi?.jabatan_nama || '-' }}
            </span>
            <span v-else-if="item.pengampu == 'tim-kerja' && item.tim_kerja">
              {{ item.tim_kerja.nama }} - {{ item.tim_kerja.ketua?.peg_nama }}
            </span>
            <span v-else>-</span>
          </b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>

    <div>
      <b-pagination
        v-model="data.current_page"
        :total-rows="data.total"
        :per-page="data.per_page"
        @change="getData($event)"
      >
        <template #page="{ page, active }">
          <i class="fa fa-spinner fa-pulse fa-fw" v-if="isBusy.getData && active"></i>
          <template v-else>{{ page }}</template>
        </template>
      </b-pagination>
    </div>
  </b-card>
</template>