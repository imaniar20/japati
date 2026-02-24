<script>
import axios from 'axios'

export default {
  name: 'FilterSasaranKinerja',
  props: {
    id: {
      type: String,
      default: 'filter-sasaran-kinerja',
    },
    labelTitle: {
      type: String,
      default: 'Filter Sasaran',
    },
    label: {
      type: String,
      default: 'sasaran',
    },
    placeholder: {
      type: String,
      default: 'Pilih sasaran',
    },
    placeholderBusy: {
      type: String,
      default: 'Sedang memuat data...',
    },
    value: {
      default: null,
    },
    model: {
      type: String,
      validator: function (value) {
        return ['kinerja-program', 'kinerja-kegiatan', 'kinerja-sub-kegiatan', 'kinerja-langkah-aksi', 'kinerja-sub-kegiatan-kab-kota'].indexOf(value) !== -1
      }
    },
    groupProps: {
      type: Object,
      default: () => {
        return {}
      }
    },
    selectProps: {
      type: Object,
      default: () => {
        return {}
      }
    },
    satker: {
      type: [String, Number],
    },
  },
  data() {
    return {
      isBusy: false,
      options: [],
      defaultGroupProps: {
        'label-class': 'font-weight-bold',
      },
    }
  },
  watch: {
    satker: function() {
      this.getData()
    }
  },
  mounted() {
    this.getData()
  },
  methods: {
    async getData() {
      this.isBusy = true

      const { data } = await axios.get(`option/sasaran/${this.model}`, {
        params: {
          satuan_kerja_id: this.satker,
        }
      })

      this.options = data
      this.isBusy = false
    }
  }
}
</script>

<template>
  <b-form-group v-bind="{...defaultGroupProps, ...groupProps}" :label="labelTitle" :label-for="id">
    <v-select :id="id"
      :value="value"
      @input="(value) => $emit('input', value)"
      :options="options"
      :placeholder="isBusy ? placeholderBusy : placeholder"
      v-bind="selectProps"
    >
    </v-select>
  </b-form-group>
</template>