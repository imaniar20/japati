<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Status Penilaian LKE</h3>
            <div class="card-tools">
              <button 
                type="button" 
                class="btn btn-primary btn-sm"
                @click="refreshData"
                :disabled="loading"
              >
                <i class="ti ti-refresh" :class="{ 'ti-spin': loading }"></i>
                Refresh
              </button>
            </div>
          </div>
          
          <div class="card-body">
            <!-- Loading State -->
            <div v-if="loading" class="text-center py-4">
              <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
              </div>
              <p class="mt-2">Memuat data...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="alert alert-danger" role="alert">
              <i class="ti ti-alert-triangle"></i>
              {{ error }}
            </div>

            <!-- Data Table -->
            <div v-else class="table-responsive">
              <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                  <tr>
                    <th 
                      scope="col" 
                      class="sortable"
                      @click="sort('satuan_kerja_nama')"
                    >
                      Satuan Kerja
                      <i class="ti ti-arrows-sort ms-1" :class="getSortIcon('satuan_kerja_nama')"></i>
                    </th>
                    <th 
                      scope="col" 
                      class="text-center sortable"
                      @click="sort('status_penilaian_awal_opd')"
                    >
                      Penilaian Awal OPD
                      <i class="ti ti-arrows-sort ms-1" :class="getSortIcon('status_penilaian_awal_opd')"></i>
                    </th>
                    <th 
                      scope="col" 
                      class="text-center sortable"
                      @click="sort('status_penilaian_awal_eval')"
                    >
                      Penilaian Awal Evaluator
                      <i class="ti ti-arrows-sort ms-1" :class="getSortIcon('status_penilaian_awal_eval')"></i>
                    </th>
                    <th 
                      scope="col" 
                      class="text-center sortable"
                      @click="sort('status_penilaian_akhir_opd')"
                    >
                      Penilaian Akhir OPD
                      <i class="ti ti-arrows-sort ms-1" :class="getSortIcon('status_penilaian_akhir_opd')"></i>
                    </th>
                    <th 
                      scope="col" 
                      class="text-center sortable"
                      @click="sort('status_penilaian_akhir_eval')"
                    >
                      Penilaian Akhir Evaluator
                      <i class="ti ti-arrows-sort ms-1" :class="getSortIcon('status_penilaian_akhir_eval')"></i>
                    </th>
                    <th 
                      scope="col" 
                      class="text-center sortable"
                      @click="sort('status_penilaian_pleno')"
                    >
                      Penilaian Pleno
                      <i class="ti ti-arrows-sort ms-1" :class="getSortIcon('status_penilaian_pleno')"></i>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in sortedData" :key="index">
                    <td>{{ item.satuan_kerja_nama }}</td>
                    <td class="text-center">
                      <span 
                        class="badge"
                        :class="item.status_penilaian_awal_opd ? 'badge-success' : 'badge-secondary'"
                      >
                        <i class="ti" :class="item.status_penilaian_awal_opd ? 'ti-check' : 'ti-time'"></i>
                        {{ item.status_penilaian_awal_opd ? 'Selesai' : 'Belum' }}
                      </span>
                    </td>
                    <td class="text-center">
                      <span 
                        class="badge"
                        :class="item.status_penilaian_awal_eval ? 'badge-success' : 'badge-secondary'"
                      >
                        <i class="ti" :class="item.status_penilaian_awal_eval ? 'ti-check' : 'ti-time'"></i>
                        {{ item.status_penilaian_awal_eval ? 'Selesai' : 'Belum' }}
                      </span>
                    </td>
                    <td class="text-center">
                      <span 
                        class="badge"
                        :class="item.status_penilaian_akhir_opd ? 'badge-success' : 'badge-secondary'"
                      >
                        <i class="ti" :class="item.status_penilaian_akhir_opd ? 'ti-check' : 'ti-time'"></i>
                        {{ item.status_penilaian_akhir_opd ? 'Selesai' : 'Belum' }}
                      </span>
                    </td>
                    <td class="text-center">
                      <span 
                        class="badge"
                        :class="item.status_penilaian_akhir_eval ? 'badge-success' : 'badge-secondary'"
                      >
                        <i class="ti" :class="item.status_penilaian_akhir_eval ? 'ti-check' : 'ti-time'"></i>
                        {{ item.status_penilaian_akhir_eval ? 'Selesai' : 'Belum' }}
                      </span>
                    </td>
                    <td class="text-center">
                      <span 
                        class="badge"
                        :class="item.status_penilaian_pleno ? 'badge-success' : 'badge-secondary'"
                      >
                        <i class="ti" :class="item.status_penilaian_pleno ? 'ti-check' : 'ti-time'"></i>
                        {{ item.status_penilaian_pleno ? 'Selesai' : 'Belum' }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>

              <!-- Empty State -->
              <div v-if="!loading && sortedData.length === 0" class="text-center py-4">
                <i class="ti ti-inbox ti-3x text-muted mb-3"></i>
                <p class="text-muted">Tidak ada data tersedia</p>
              </div>
            </div>

            <!-- Summary Cards -->
            <div v-if="!loading && sortedData.length > 0" class="row mt-4">
              <div class="col-md-2">
                <div class="card bg-primary text-white">
                  <div class="card-body">
                    <div class="d-flex justify-content-between">
                      <div>
                        <h4>{{ summary.total }}</h4>
                        <small>Total OPD</small>
                      </div>
                      <i class="ti ti-user ti-2x"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="card bg-success text-white">
                  <div class="card-body">
                    <div class="d-flex justify-content-between">
                      <div>
                        <h4>{{ summary.awalOpd }}</h4>
                        <small>Penilaian Awal OPD</small>
                      </div>
                      <i class="ti ti-check ti-2x"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="card bg-info text-white">
                  <div class="card-body">
                    <div class="d-flex justify-content-between">
                      <div>
                        <h4>{{ summary.awalEval }}</h4>
                        <small>Penilaian Awal Evaluator</small>
                      </div>
                      <i class="ti ti-pencil ti-2x"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="card bg-warning text-white">
                  <div class="card-body">
                    <div class="d-flex justify-content-between">
                      <div>
                        <h4>{{ summary.akhirOpd }}</h4>
                        <small>Penilaian Akhir OPD</small>
                      </div>
                      <i class="ti ti-layers ti-2x"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="card bg-secondary text-white">
                  <div class="card-body">
                    <div class="d-flex justify-content-between">
                      <div>
                        <h4>{{ summary.akhirEval }}</h4>
                        <small>Penilaian Akhir Evaluator</small>
                      </div>
                      <i class="ti ti-announcement ti-2x"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="card bg-dark text-white">
                  <div class="card-body">
                    <div class="d-flex justify-content-between">
                      <div>
                        <h4>{{ summary.pleno }}</h4>
                        <small>Penilaian Pleno</small>
                      </div>
                      <i class="ti ti-flag ti-2x"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'StatusPenilaian',
  data() {
    return {
      data: [],
      loading: false,
      error: null,
      sortKey: '',
      sortOrder: 'asc'
    }
  },
  computed: {
    sortedData() {
      if (!this.sortKey) return this.data
      
      return [...this.data].sort((a, b) => {
        let aVal = a[this.sortKey]
        let bVal = b[this.sortKey]
        
        // Handle string comparison
        if (typeof aVal === 'string') {
          aVal = aVal.toLowerCase()
          bVal = bVal.toLowerCase()
        }
        
        if (this.sortOrder === 'asc') {
          return aVal > bVal ? 1 : aVal < bVal ? -1 : 0
        } else {
          return aVal < bVal ? 1 : aVal > bVal ? -1 : 0
        }
      })
    },
    summary() {
      if (!this.data.length) return {}
      
      return {
        total: this.data.length,
        awalOpd: this.data.filter(item => item.status_penilaian_awal_opd === 1).length,
        awalEval: this.data.filter(item => item.status_penilaian_awal_eval === 1).length,
        akhirOpd: this.data.filter(item => item.status_penilaian_akhir_opd === 1).length,
        akhirEval: this.data.filter(item => item.status_penilaian_akhir_eval === 1).length,
        pleno: this.data.filter(item => item.status_penilaian_pleno === 1).length,
      }
    }
  },
  methods: {
    async fetchData() {
      this.loading = true
      this.error = null
      
      try {
        const response = await axios.get('lke/penilaian/status-penilaian')
        this.data = response.data
      } catch (error) {
        console.error('Error fetching data:', error)
        this.error = 'Gagal memuat data. Silakan coba lagi.'
      } finally {
        this.loading = false
      }
    },
    refreshData() {
      this.fetchData()
    },
    sort(key) {
      if (this.sortKey === key) {
        this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc'
      } else {
        this.sortKey = key
        this.sortOrder = 'asc'
      }
    },
    getSortIcon(key) {
      if (this.sortKey !== key) return 'ti-arrows-sort'
      return this.sortOrder === 'asc' ? 'ti-sort-ascending' : 'ti-sort-descending'
    }
  },
  mounted() {
    this.fetchData()
  }
}
</script>

<style scoped>
.sortable {
  cursor: pointer;
  user-select: none;
  transition: background-color 0.2s;
}

.sortable:hover {
  background-color: rgba(0, 0, 0, 0.1);
}

.badge {
  font-size: 0.875rem;
  padding: 0.375rem 0.75rem;
}

.badge-success {
  background-color: #28a745;
}

.badge-secondary {
  background-color: #6c757d;
}

.card {
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  border: 1px solid rgba(0, 0, 0, 0.125);
}

.table th {
  border-top: none;
  font-weight: 600;
}

.ti-spin {
  animation: ti-spin 1s infinite linear;
}

.ti-2x {
  font-size: 2em;
}

.ti-3x {
  font-size: 3em;
}

@keyframes ti-spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>