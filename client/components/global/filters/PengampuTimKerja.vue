<script>
import axios from 'axios'

export default {
  name: 'FilterPengampuTimKerja',
  props: {
    id: {
      type: String,
      default: 'filter-pengampu-tim-kerja',
    },
    reduce: {
      type: Function,
      default: (opt) => opt.id,
    },
    labelTitle: {
      type: String,
      default: 'Filter Pengampu Tim Kerja',
    },
    label: {
      type: String,
      default: 'nama',
    },
    placeholder: {
      type: String,
      default: 'Pilih tim kerja',
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

      const { data } = await axios.get('option/pengampu/tim-kerja', {
        params: {
          satuan_kerja_id: this.satuanKerjaId,
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
      :filterBy="$helper.vSelectFilterBy('nama', 'ketua.peg_nama')"
    >
      <template #option="{ nama, ketua }">
        {{ nama }} - {{ ketua?.peg_nama }}
      </template>
      <template #selected-option="{ nama, ketua }">
        {{ nama }} - {{ ketua?.peg_nama }}
      </template>
    </v-select>
  </b-form-group>
</template>