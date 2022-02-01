import Vue from 'vue'
import App from './App.vue'
import router from './router'
import vuetify from './plugins/vuetify'
import "@mdi/font/css/materialdesignicons.css";

Vue.config.productionTip = false
/*ESTA DIRECCION DEBE MODIFICARSE AL CAMBIAR DE SERVIDOR*/
Vue.prototype.$my_host = 'http://192.168.0.8'

new Vue({
  router,
  vuetify,
  render: h => h(App)
}).$mount('#app')
