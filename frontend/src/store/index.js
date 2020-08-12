import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    dadosUser: null,
    token: ''
  },
  mutations: {},
  actions: {
    login({ state }, dadosLogin) {
      return new Promise((resolve, reject) => {
        Vue.axios.post("login", dadosLogin)
        .then(response => {
          const { access_token, user: { nome, perfil } } = response.data;
          
          state.dadosUser = { nome, perfil };

          Vue.axios.defaults.headers.common['Authorization'] = access_token;
          state.token = access_token;

          resolve(true);
        })
        .catch(error => {
          reject(error);
        });
      });
    }
  },
  modules: {},
  getters: {
    isLoggedIn: state => !!state.token,
    getDadosUser: state => state.dadosUser
  }
});
