<script>
import axios from 'axios'

export default {
  name: 'FilterPengampuUnitKerja',
  props: {
    id: {
      type: String,
      default: 'filter-pengampu-unit-kerja',
    },
    reduce: {
      type: Function,
      default: (opt) => opt.id,
    },
    labelTitle: {
      type: String,
      default: 'Filter Pengampu Unit Kerja',
    },
    label: {
      type: String,
      default: 'unit_kerja_nama',
    },
    placeholder: {
      type: String,
      default: 'Pilih unit kerja',
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
    satuanKerjaId: {
      type: Number,
      required: true,
    },
    type: {
      type: String,
      required: true,
      validator: function (value) {
        return ['kinerja-program', 'kinerja-kegiatan', 'kinerja-sub-kegiatan'].indexOf(value) !== -1
      },
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

  mounted () {
    this.getData()
  },

  methods: {
    async getData() {
      this.isBusy = true

      const { data } = await axios.get('option/pengampu/unit-kerja', {
        params: {
          satuan_kerja_id: this.satuanKerjaId,
          type: this.type,
        }
      })

      this.options = data
      this.isBusy = false
    },
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