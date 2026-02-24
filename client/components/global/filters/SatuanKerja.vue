<script>
import axios from 'axios'

export default {
  name: 'FilterSatuanKerja',
  props: {
    id: {
      type: String,
      default: 'filter-satker',
    },
    reduce: {
      type: Function,
      default: (opt) => opt.satuan_kerja_id,
    },
    labelTitle: {
      type: String,
      default: 'Filter Satuan Kerja',
    },
    label: {
      type: String,
      default: 'satuan_kerja_nama',
    },
    placeholder: {
      type: String,
      default: 'Pilih satuan kerja',
    },
    placeholderBusy: {
      type: String,
      default: 'Sedang memuat data...',
    },
    value: {
      default: null,
    },
    isSetda: {
      type: Boolean,
      default: false,
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
    satuanKerjaIds: {
      type: Array,
      default: () => []
    }
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

      const { data } = await axios.get('option/satuan-kerja', {
        params: {
          satuan_kerja_id: this.isSetda ? this.$const.SATKER_SETDA : null,
          satuan_kerja_ids: this.satuanKerjaIds,
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
      :reduce="reduce"
      :label="label"
      :placeholder="isBusy ? placeholderBusy : placeholder"
      v-bind="selectProps"
    >
    </v-select>
  </b-form-group>
</template>