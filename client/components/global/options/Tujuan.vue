<script>
import axios from 'axios'

export default {
  name: 'OptionTujuan',
  props: {
    id: {
      type: String,
      default: 'option-tujuan',
    },
    reduce: {
      type: Function,
      default: (opt) => opt.id,
    },
    labelTitle: {
      type: String,
      default: 'Tujuan',
    },
    label: {
      type: String,
      default: 'tujuan',
    },
    placeholder: {
      type: String,
      default: 'Pilih tujuan',
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
    misiId: {
      default: null,
    },
    dependsOnMisi: {
      type: Boolean,
      default: false,
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
  watch: {
    misiId(newValue, oldValue) {
      if (newValue !== oldValue) {
        this.$emit('input', null)
        this.getData()
      }
    },
  },
  methods: {
    async getData() {
      if (this.dependsOnMisi && !this.misiId) {
        this.options = []
        return
      }

      this.isBusy = true

      const { data } = await axios.get('option/tujuan', {
        params: {
          ids: this.ids,
          misi_id: this.misiId,
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
      :disabled="dependsOnMisi && !misiId"
      :clearable="!required"
    >
      <template #option="opt">
        {{ opt.nomor }}. {{ opt.tujuan }}
      </template>
      <template #selected-option="opt">
        {{ opt.nomor }}. {{ opt.tujuan }}
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
