import Vue from "vue";
import Vuex from "vuex";
import axios from "../router";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    dadosUser: null,
    token: localStorage.getItem('token') || ''
  },
  mutations: {},
  actions: {
    start({ state }) {
      return new Promise((resolve, reject) => {
        if(state.token === '')
          reject();

        Vue.axios.defaults.headers.common['Authorization'] = state.token;

        resolve();
      })
    },
    login({ state }, dadosLogin) {
      return new Promise((resolve, reject) => {
        Vue.axios.post("login", dadosLogin)
        .then(response => {
          const { access_token, user: { nome, perfil } } = response.data;
          
          state.dadosUser = { nome, perfil };

          Vue.axios.defaults.headers.common['Authorization'] = access_token;
          localStorage.setItem('token', access_token);

          resolve(true);
        })
        .catch(error => {
          localStorage.removeItem('token');

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
