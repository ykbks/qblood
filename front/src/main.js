// css
import 'element-ui/lib/theme-default/index.css'
import '../static/css/template.css'
import '../static/css/style.css'
// js
import Vue from 'vue'
import VueResource from 'vue-resource'
import App from './App'
import router from './router'
import 'bootstrap/dist/js/bootstrap'
import ElementUI from 'element-ui'
import locale from 'element-ui/lib/locale/lang/en'

Vue.use(ElementUI, { locale })
Vue.use(VueResource)

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: {
    App
  }
})
