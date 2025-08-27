import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './assets/styles/variables.css';


import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'


Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

Vue.config.productionTip = false
//pra parar de aparecer o erro no console pq estou usando a versão 2.7 do vue
const warn = console.warn
console.warn = (msg, ...args) => {
  if (
    typeof msg === 'string' &&
    (msg.includes('$attrs is readonly') || msg.includes('$listeners is readonly'))
  ) {
    return
  }
  warn(msg, ...args)
}
new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
