<script>
import axios from 'axios'
import DiagramSasaran from '~/components/global/DiagramSasaran.vue'

export default {
  layout: 'guest',

  components: {
    DiagramSasaran,
  },

  async asyncData() {
    const { data } = await axios.get('/public-display/arsitektur-kinerja')

    return {
      data,
    }
  },

  data() {
    return {
      selectedIndex: 1, // first load v-select tidak bisa pakai nilai 0, jadi dimulai dari 1
      options: {
        sasaran: []
      },
      componentKey: 0,
    }
  },

  computed: {
    selectedData: function () {
      return [this.data[this.selectedIndex - 1]]
    }
  },

  mounted() {
    this.options.sasaran = this.data.map((el, index) => {
      return {
        id: index + 1,
        label: `${index + 1}. ${el.sasaran_strategis.sasaran} - ${el.indikator_sasaran_strategis.indikator}`,
      }
    })
  },

  watch: {
    selectedIndex: function () {
      this.componentKey++
    }
  }
}
</script>

<template>
  <b-card>
    <div v-if="!data.length" class="text-center my-4 font-weight-bold">
      <div>Tidak ada data</div>
    </div>
    <div v-else>
      <b-form-group label="Pilih sasaran">
        <v-select
          v-model="selectedIndex"
          :options="options.sasaran"
          :reduce="opt => opt.id"
          :clearable="false"
          placeholder="Pilih sasaran"
        >
        </v-select>
      </b-form-group>

      <DiagramSasaran :data="selectedData" type="arsitektur-kinerja" :key="componentKey" :tahunKinerja="$helper.getTahunKinerjaPublic()" />
    </div>
  </b-card>
</template>