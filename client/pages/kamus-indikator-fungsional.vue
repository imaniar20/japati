<script>
import KamusIndikatorFungsional from '~/components/kamus-indikator-fungsional/KamusIndikatorFungsional.vue'
import KamusIndikatorFungsionalManual from '~/components/kamus-indikator-fungsional/KamusIndikatorFungsionalManual.vue'

export default {
  middleware: 'auth',

  components: {
    KamusIndikatorFungsional,
    KamusIndikatorFungsionalManual,
  },
  
  data() {
    return {
      filter: {
        satuan_kerja_id: 1030,
      },
    }
  }
}
</script>

<template>
  <div>
    <KamusIndikatorFungsional @satker="filter.satuan_kerja_id = $event" class="mb-3" />
    <KamusIndikatorFungsionalManual v-if="filter.satuan_kerja_id" :satkerId="filter.satuan_kerja_id" />
  </div>
</template>