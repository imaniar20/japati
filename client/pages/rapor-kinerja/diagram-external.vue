<script>
import TriwulanOption from '~/components/TriwulanOption.vue'
import DiagramRaporKinerja from '~/components/DiagramRaporKinerja.vue'

export default {
  components: {
    TriwulanOption,
    DiagramRaporKinerja,
  },

  middleware: ['auth'],
 
  async asyncData({ params, redirect, store, query, $role }) {
    const triwulan = parseInt(params.triwulan)
    
    if (![1, 2, 3, 4].includes(triwulan)) {
      redirect('/rapor-kinerja/1/diagram-external')
      return false
    }

    return {
      triwulan,
      satuanKerjaId: $role.isSuper() || $role.isviewRaporKinerja() ? parseInt(query.satuan_kerja_id) : store.getters['auth/user'].satuan_kerja_id,
      key: 1,
    }
  },

  watch: {
    triwulan: function(val) {
      this.$router.push({
        path: `/rapor-kinerja/${val}/diagram-external`,
        query: {
          satuan_kerja_id: this.satuanKerjaId
        }
      })
    },
    satuanKerjaId: function() {
      this.key++
    }
  },
}
</script>

<template>
  <div>
    <b-card class="mb-2">
      <b-row>
        <b-col>
          <TriwulanOption v-model="triwulan" />
        </b-col>
        <b-col v-if="$role.isSuper() || $role.isviewRaporKinerja()">
          <FilterSatuanKerja v-model="satuanKerjaId" labelTitle="Satuan Kerja" />
        </b-col>
      </b-row>
    </b-card>

    <DiagramRaporKinerja v-if="satuanKerjaId" :key="key" :triwulan="triwulan" :satuanKerjaId="satuanKerjaId" :isExternal="true" />
  </div>
</template>