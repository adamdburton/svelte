require('./bootstrap');

import Vue from 'vue'

import App from './components/App.vue';

Vue.config.ignoredElements = [/^a-/];

const app = new Vue({
  el: '#app',
  components: {
    App
  },
  render: h => h(App)
});

window.Vue = app;