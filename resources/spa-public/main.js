import './assets/style/index.scss';

import 'babel-polyfill';

import Vue from 'vue';

import Router from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import App from './App.vue';

Vue.use(Router);
Vue.use(VueAxios, axios);

import ROUTES from './routes'

import User from './model/User';

Vue.prototype.$User = new User();

var router =  new Router({
	routes: ROUTES
});

new Vue({
  el: '#app',
  router,
  render: h => h(App)
});
