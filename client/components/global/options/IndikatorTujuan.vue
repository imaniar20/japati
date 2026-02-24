<script>
import axios from 'axios'

export default {
  name: 'OptionIndikatorTujuan',
  props: {
    id: {
      type: String,
      default: 'option-indikator-tujuan',
    },
    reduce: {
      type: Function,
      default: (opt) => opt.id,
    },
    labelTitle: {
      type: String,
      default: 'Indikator Tujuan',
    },
    label: {
      type: String,
      default: 'indikator',
    },
    placeholder: {
      type: String,
      default: 'Pilih indikator tujuan',
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
    required: {
      type: Boolean,
      default: true,
    },
    ids: {
      type: Array,
      default: () => []
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

      const { data } = await axios.get('option/indikator-tujuan', {
        params: {
          ids: this.ids,
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
      <template #option="opt">
        {{ opt.nomor }}. {{ opt.indikator }}
      </template>
      <template #selected-option="opt">
        {{ opt.nomor }}. {{ opt.indikator }}
      </template>
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