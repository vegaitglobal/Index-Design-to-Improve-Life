// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import {ClientTable, Event} from 'vue-tables-2';
import JsonExcel from 'vue-json-excel'

Vue.use(ClientTable, {}, false, 'bootstrap4')
 
Vue.component('downloadExcel', JsonExcel)


Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
