<script>
  import axios from 'axios'
  import TriwulanOption from '~/components/TriwulanOption.vue'
  
  export default {
    middleware: ['auth'],
  
    components: {
      TriwulanOption,
    },
  
    async asyncData({ params, redirect }) {
      const triwulan = parseInt(params.triwulan)
      
      if (![1, 2, 3, 4].includes(triwulan)) {
        redirect('/rapor-kinerja/1/rank')
        return false
      }
  
      return {
        triwulan,
      }
    },
  
    data() {
      return {
        data: [],
        isBusy: {
          getData: false,
          exportExcel: false,
        },
        table: {
          fields: [
            { key: 'rank', label: 'Rank' },
            { key: 'satuan_kerja_nama', label: 'Satuan Kerja/Biro' },
            { key: 'capaian', label: 'Capaian Kinerja' },
            { key: 'efisiensi_anggaran', label: 'Efisiensi' },
            { key: 'anggaran', label: 'Jumlah Anggaran' },
            { key: 'anggaran_terserap', label: 'Anggaran yang digunakan' },
            { key: 'anggaran_tidak_terserap', label: 'Anggaran yang tidak digunakan' },
            { key: 'terserap', label: '% Anggaran yang digunakan' },
            { key: 'tidak_terserap', label: '% Anggaran yang tidak digunakan' },
            { key: 'penambahan_jumlah_output', label: 'Penambahan Jumlah Output' },
          ]
        },
        filter: {
          // is_external: 0,
        }
      }
    },
  
    watch: {
      triwulan: function (val) {
        this.$router.push(`/rapor-kinerja/${val}/rank`)
      },
    },
  
    mounted() {
      this.getData()
    },
  
    methods: {
      async getData() {
        this.isBusy.getData = true
  
        try {
          const { data } = await axios.get(`/rapor-kinerja/${this.triwulan}/report`, {
            params: this.filter
          })
  
          this.data = data
        } catch (error) {
          alert('Gagal mengambil data rapor kinerja')
        } finally {
          this.isBusy.getData = false
        }
      },
      async exportExcel() {
        this.isBusy.exportExcel = true

        try {
          const { data } = await axios.get(`/rapor-kinerja/${this.triwulan}/report/export`, {
            params: {
              filter: this.filter,
            },
            responseType: 'blob'
          })

          const url = window.URL.createObjectURL(new Blob([data]))
          const link = document.createElement('a')

          link.href = url
          link.setAttribute('download', `Ranking Rapor Kinerja Triwulan ${this.triwulan}.xlsx`)
          document.body.appendChild(link)
          link.click()
        } catch (error) {
          throw error
        } finally {
          this.isBusy.exportExcel = false
        }
      }
    }
  }
  </script>
  
  <template>
    <div>
      <b-card class="mb-2">
        <div class="text-right">
          <b-button variant="success" @click="exportExcel" :disabled="isBusy.exportExcel">
            <b-spinner small v-if="isBusy.exportExcel"></b-spinner>
            <i v-else class="ti-download" aria-hidden="true"></i>
            Export
          </b-button>
        </div>
        <TriwulanOption v-model="triwulan" />
      </b-card>
      <b-card>

        <b-table responsive hover sticky-header="calc(100vh - 200px)" :fields="table.fields" :items="data" :busy="isBusy.getData" show-empty class="table-bordered" head-variant="info">
          <template #cell(anggaran)="row">
            {{ row.item.anggaran | rupiah }}
          </template>
          <template #cell(anggaran_terserap)="row">
            {{ row.item.anggaran_terserap | rupiah }}
          </template>
          <template #cell(anggaran_tidak_terserap)="row">
            {{ row.item.anggaran_tidak_terserap | rupiah }}
          </template>
          <template #cell(terserap)="row">
            {{ row.item.anggaran ? ((row.item.anggaran_terserap / row.item.anggaran) * 100) : 0 | decimalDigit }}
          </template>
          <template #cell(tidak_terserap)="row">
            {{ row.item.anggaran ? ((row.item.anggaran_tidak_terserap / row.item.anggaran) * 100) : 0 | decimalDigit }}
          </template>
          <template #cell(penambahan_jumlah_output)="row">
            {{ row.item.perubahanJumlahOutput || '-' }}
          </template>
        </b-table>
      </b-card>
    </div>
  </template>