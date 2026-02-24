import Vue from 'vue'
import panZoom from 'panzoom';
import VuePanZoom from 'vue-panzoom/src/components/pan-zoom/component.vue';

Vue.prototype.$panZoom = panZoom; //this is very important
Vue.component('VuePanZoom', VuePanZoom);
