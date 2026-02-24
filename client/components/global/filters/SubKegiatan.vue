<script>
import axios from 'axios'

export default {
  name: 'FilterSubKegiatan',
  props: {
    id: {
      type: String,
      default: 'filter-sub-kegiatan',
    },
    reduce: {
      type: Function,
      default: (opt) => opt.id,
    },
    labelTitle: {
      type: String,
      default: 'Filter Sub Kegiatan',
    },
    label: {
      type: String,
      default: 'nama',
    },
    placeholder: {
      type: String,
      default: 'Pilih Sub Kegiatan',
    },
    placeholderBusy: {
      type: String,
      default: 'Sedang memuat data...',
    },
    placeholderEmptySatker: {
      type: String,
      default: 'Pilih satuan kerja terlebih dahulu',
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
    satkerId: {
      default: null
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
  watch: {
    satkerId: function () {
      this.getData()
    }
  },
  mounted() {
    this.getData()
  },
  methods: {
    async getData() {
      if (!this.satkerId) {
        return false
      }

      this.isBusy = true

      const { data } = await axios.get(`option/sub-kegiatan/${this.satkerId}`)

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
      :placeholder="isBusy ? placeholderBusy : (satkerId ? placeholder : placeholderEmptySatker)"
      v-bind="selectProps"
    >
    </v-select>
  </b-form-group>
</template>