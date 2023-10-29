
import Vue from "vue";
import App from "./App";
import router from "./router/index";

import PaperDashboard from "./plugins/paperDashboard";
import "vue-notifyjs/themes/default.css";
import Vuetify from 'vuetify';
import toast from '../assets/js/services/toast';

Vue.prototype.$http = Axios;
Vue.prototype.$toast = toast;
// import 'vuetify/dist/vuetify.min.css'

import Axios from 'axios';

Vue.prototype.$http = Axios;
Vue.prototype.$toast = toast;
// Vue.prototype.$url = 'http://mgm.pradopower.org'

Vue.use(PaperDashboard);
Vue.use(Vuetify)
/* eslint-disable no-new */
new Vue({
  router,
  vuetify: new Vuetify({
    themes: {
      dark: {
        background: '#292930',
      },
    },
      dark: true,
  }),
  render: h => h(App)
}).$mount("#app"); 
