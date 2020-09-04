import Vue from "vue";
import "./plugins/axios";
import "./styles/root.css";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import vuetify from "./plugins/vuetify";
import { VueMaskDirective } from 'v-mask';
import moment from 'moment';
import VueSweetalert2 from 'vue-sweetalert2';

Vue.prototype.moment = moment;

Vue.config.productionTip = false;

Vue.directive('mask', VueMaskDirective);

Vue.use(VueSweetalert2);

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (store.getters.isLoggedIn) {
        next()
        return
    }
    next('/login')
  } else {
    next()
  }
});

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App),
}).$mount("#app");
