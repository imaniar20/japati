<script>
import axios from 'axios'

export default {
  name: 'FilterPengampu',
  props: {
    id: {
      type: String,
      default: 'filter-pengampu',
    },
    labelTitle: {
      type: String,
      default: 'Filter Pengampu',
    },
    placeholder: {
      type: String,
      default: 'Pilih pengampu',
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
      options: [
        {id: '-', label: 'Belum ada pengampu'},
        {id: 'tim-kerja', label: 'Tim Kerja'},
        {id: 'unit-kerja', label: 'Unit Kerja'},
      ],
      defaultGroupProps: {
        'label-class': 'font-weight-bold',
      },
    }
  },
}
</script>

<template>
  <b-form-group v-bind="{...defaultGroupProps, ...groupProps}" :label="labelTitle" :label-for="id">
    <v-select :id="id"
      :value="value"
      @input="(value) => $emit('input', value)"
      :options="options"
      label="label"
      :reduce="(opt) => opt.id"
      :placeholder="placeholder"
      v-bind="selectProps"
    >
    </v-select>
  </b-form-group>
</template>