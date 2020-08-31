import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    dadosUser: null,
    token: '',
    loading: false
  },
  mutations: {},
  actions: {
    login({ state }, dadosLogin) {
      return new Promise((resolve, reject) => {
        Vue.axios.post("login", dadosLogin)
        .then(response => {
          const { access_token, user: { id, nome, perfil, unidade_saude_id } } = response.data;
          
          state.dadosUser = { id, nome, perfil, unidade_saude_id };

          Vue.axios.defaults.headers.common['Authorization'] = access_token;
          state.token = access_token;

          resolve(true);
        })
        .catch(error => {
          reject(error);
        });
      });
    },
    setLoading({state}) {
      state.loading = !state.loading;
    }
  },
  modules: {},
  getters: {
    isLoggedIn: state => !!state.token,
    getDadosUser: state => state.dadosUser,
  }
});
