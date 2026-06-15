<script>
import axios from 'axios'

export default {
  name: 'OptionMisi',
  props: {
    id: {
      type: String,
      default: 'option-misi',
    },
    reduce: {
      type: Function,
      default: (opt) => opt.id,
    },
    labelTitle: {
      type: String,
      default: 'Misi',
    },
    label: {
      type: String,
      default: 'misi',
    },
    placeholder: {
      type: String,
      default: 'Pilih misi',
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
    visiId: {
      default: null,
    },
    dependsOnVisi: {
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
    visiId(newValue, oldValue) {
      if (newValue !== oldValue) {
        this.$emit('input', null)
        this.getData()
      }
    },
  },
  methods: {
    async getData() {
      if (this.dependsOnVisi && !this.visiId) {
        this.options = []
        return
      }

      this.isBusy = true

      const { data } = await axios.get('option/misi', {
        params: {
          ids: this.ids,
          visi_id: this.visiId,
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
      :disabled="dependsOnVisi && !visiId"
      :clearable="!required"
    >
      <template #option="opt">
        {{ opt.nomor }}. {{ opt.misi }}
      </template>
      <template #selected-option="opt">
        {{ opt.nomor }}. {{ opt.misi }}
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
