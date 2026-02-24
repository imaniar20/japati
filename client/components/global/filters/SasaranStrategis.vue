<script>
import axios from 'axios'

export default {
  name: 'FilterSasaranStrategis',
  props: {
    id: {
      type: String,
      default: 'filter-sasaran-strategis',
    },
    reduce: {
      type: Function,
      default: (opt) => opt.id,
    },
    labelTitle: {
      type: String,
      default: 'Filter Sasaran Strategis',
    },
    label: {
      type: String,
      default: 'sasaran',
    },
    placeholder: {
      type: String,
      default: 'Pilih sasaran strategis',
    },
    placeholderBusy: {
      type: String,
      default: 'Sedang memuat data...',
    },
    value: {
      default: null,
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
  mounted() {
    this.getData()
  },
  methods: {
    async getData() {
      this.isBusy = true

      const { data } = await axios.get('option/sasaran-strategis')

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
      :reduce="reduce"
      :label="label"
      :placeholder="isBusy ? placeholderBusy : placeholder"
      v-bind="selectProps"
    >
      <template #option="opt">
        {{ opt.nomor }}. {{ opt.sasaran }}
      </template>
      <template #selected-option="opt">
        {{ opt.nomor }}. {{ opt.sasaran }}
      </template>
    </v-select>
  </b-form-group>
</template>