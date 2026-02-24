<script>
import axios from 'axios'

export default {
  name: 'OptionSatuanKerja',
  props: {
    id: {
      type: String,
      default: 'option-satker',
    },
    reduce: {
      type: Function,
      default: (opt) => opt.satuan_kerja_id,
    },
    labelTitle: {
      type: String,
      default: 'Satuan Kerja',
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
    required: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      isBusy: false,
      options: [],
      defaultGroupProps: {
        'label-class': 'font-weight-bold',
        'label-cols': 12,
        'label-cols-md': 2,
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
          satuan_kerja_id: this.isSetda ? this.$const.SATKER_SETDA : null
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
      :clearable="!required"
    >
      <template #search="{attributes, events}">
        <input
          class="vs__search"
          :required="required ? !value : false"
          v-bind="attributes"
          v-on="events"
        />
      </template>
    </v-select>
  </b-form-group>
</template>