"use strict";

import Vue from "vue";
import axios from "axios";

import store from "../store";

// Full config:  https://github.com/axios/axios#request-config
// axios.defaults.baseURL = process.env.baseURL || process.env.apiUrl || '';
// axios.defaults.headers.common['Authorization'] = AUTH_TOKEN;
// axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';

let config = {
  // baseURL: process.env.baseURL || process.env.apiUrl || ""
  // timeout: 60 * 1000, // Timeout
  // withCredentials: true, // Check cross-site Access-Control
  baseURL: process.env.VUE_APP_API_URL || "http://localhost:8000/api/",
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
};

const _axios = axios.create(config);

_axios.interceptors.request.use(
  function(config) {
    // Do something before request is sent
    store.dispatch('setLoading');
    return config;
  },
  function(error) {
    // Do something with request error
    store.dispatch('setLoading');
    return Promise.reject(error);
  }
);

// Add a response interceptor
_axios.interceptors.response.use(
  function(response) {
    // Do something with response data
    store.dispatch('setLoading');
    return response;
  },
  function(error) {
    // Do something with response error
    store.dispatch('setLoading');
    return Promise.reject(error);
  }
);

Plugin.install = function(Vue, options) {
  Vue.axios = _axios;
  window.axios = _axios;
  Object.defineProperties(Vue.prototype, {
    axios: {
      get() {
        return _axios;
      }
    },
    $axios: {
      get() {
        return _axios;
      }
    }
  });
};

Vue.use(Plugin);

export default Plugin;
