<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  middleware: ['auth', 'role-super'],

  data() {
    return {
      data: [],
      isBusy: {
        getData: false,
        store: false
      },
      form: {},
      options: {
        nilai: []
      }
    }
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData() {
      this.isBusy.getData = true

      const { data: { data, options } } = await axios.get('nilai-jenjang-kinerja')

      data.forEach(value => {
        this.$set(this.form, value.satuan_kerja_id, {})
        value.iku.forEach(iku => {
          this.$set(this.form[value.satuan_kerja_id], iku.id, iku.nilai_id)
        });
      });

      this.options.nilai = options
      this.data = data
      this.isBusy.getData = false
    },

    async submitNilai() {
      try {
        this.isBusy.store = true

        await axios.post('nilai-jenjang-kinerja', {
          nilai: this.form
        })
        Swal.fire({
          type: 'success',
          title: 'Berhasil',
          text: 'Berhasil simpan data'
        })
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: 'Gagal',
          text: 'Gagal simpan data'
        })
      } finally {
        this.isBusy.store = false
      }
    },
    calculateAverage(satkerId) {
      const data = Object.values(this.form[satkerId])
      const sum = data.reduce((acc, id) => acc + Number(this.options.nilai.find(_ => _.id == id)?.nilai || 0), 0)

      return sum / data.length || 0
    }
  }
}
</script>

<template>
  <b-card>
    <div class="text-right mb-2">
      <b-button @click="submitNilai" :disabled="isBusy.store" variant="primary">
        <i v-if="isBusy.store" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
        Simpan
      </b-button>
    </div>

    <b-table-simple :aria-busy="isBusy.getData" responsive bordered striped hover>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th>Satuan Kerja</b-th>
          <b-th>Nilai Akhir</b-th>
          <b-th>IKU</b-th>
          <b-th style="width: 30%;">Nilai</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr v-if="isBusy.getData">
          <b-td colspan="4" class="text-center"><b-spinner /></b-td>
        </b-tr>
        <template v-else v-for="(item, indexSatker) of data">
          <b-tr v-for="(iku, index) of item.iku" :key="`${indexSatker}-${index}`">
            <b-td v-if="index == 0" :rowspan="item.iku.length">{{ item.satuan_kerja_nama }}</b-td>
            <b-td v-if="index == 0" :rowspan="item.iku.length">{{ calculateAverage(item.satuan_kerja_id) }}</b-td>
            <b-td>{{ iku.iku }} </b-td>
            <b-td>
              <b-form-group>
                <b-form-select v-model="form[item.satuan_kerja_id][iku.id]" :options="options.nilai" value-field="id" text-field="label"></b-form-select>
              </b-form-group>
            </b-td>
          </b-tr>
        </template>
      </b-tbody>
    </b-table-simple>
  </b-card>
</template>