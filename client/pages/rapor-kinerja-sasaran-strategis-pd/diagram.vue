<script>
import TriwulanOption from '~/components/TriwulanOption.vue'
import DiagramKinerja from '~/components/rapor-kinerja-sasaran-strategis-pd/Kinerja.vue'
import DiagramRank from '~/components/rapor-kinerja-sasaran-strategis-pd/Rank.vue'
import DiagramCapaian from '~/components/rapor-kinerja-sasaran-strategis-pd/Capaian.vue'

export default {
  components: {
    TriwulanOption,
    DiagramKinerja,
    DiagramRank,
    DiagramCapaian,
  },

  middleware: ['auth'],
 
  async asyncData({ params, redirect, store, query, $role }) {
    const triwulan = parseInt(params.triwulan)
    
    if (![1, 2, 3, 4].includes(triwulan)) {
      redirect('/rapor-kinerja-sasaran-strategis-pd/1/diagram')
      return false
    }

    return {
      triwulan,
      satuanKerjaId: $role.isSuper() ? parseInt(query.satuan_kerja_id) : store.getters['auth/user'].satuan_kerja_id,
      key: 1,
    }
  },

  watch: {
    triwulan: function(val) {
      this.$router.push({
        path: `/rapor-kinerja-sasaran-strategis-pd/${val}/diagram`,
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
        <b-col v-if="$role.isSuper()">
          <FilterSatuanKerja v-model="satuanKerjaId" :selectProps="{clearable: false}" labelTitle="Satuan Kerja" />
        </b-col>
        <b-col v-if="satuanKerjaId">
          <TriwulanOption v-model="triwulan" />
        </b-col>
      </b-row>
    </b-card>

    <template v-if="satuanKerjaId">
      <DiagramRank :key="key" :satuanKerjaId="satuanKerjaId" :triwulan="triwulan" class="mb-2" />

      <DiagramKinerja :key="`${key}-DiagramKinerja`" :triwulan="triwulan" :satuanKerjaId="satuanKerjaId" class="mb-2" />

      <b-row>
        <b-col>
          <b-card>
            <h4 class="text-center mb-3">Internal</h4>
            <DiagramCapaian :key="`${key}-DiagramCapaian-ex`" :triwulan="triwulan" :satuanKerjaId="satuanKerjaId" :isExternal="false" />
          </b-card>
        </b-col>
        <b-col>
          <b-card>
            <h4 class="text-center mb-3">Eksternal</h4>
            <DiagramCapaian :key="`${key}-DiagramCapaian-in`" :triwulan="triwulan" :satuanKerjaId="satuanKerjaId" :isExternal="true" />
          </b-card>
        </b-col>
      </b-row>
    </template>
  </div>
</template>