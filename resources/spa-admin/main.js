import 'babel-polyfill';

import 'vuetify/dist/vuetify.css';
import "vue-wysiwyg/dist/vueWysiwyg.css";

import Vue from 'vue';

import Router from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import Vuetify from 'vuetify';
import VeeValidate from 'vee-validate';
import App from './App.vue';

import moment from 'moment';
import moment_countdown from 'moment-countdown';

import wysiwyg from "vue-wysiwyg";
Vue.use(wysiwyg, {});

Vue.use(Router);
Vue.use(Vuetify);
Vue.use(VeeValidate);
Vue.use(VueAxios, axios);

import ROUTES from './routes'

import User from './model/User';

Vue.prototype.$User = new User();

var router =  new Router({
	routes: ROUTES
});

router.beforeEach( (to, from, next ) => {
	
	if( to.path === '/login') next(); // can check prototype aswell
  
	Vue.prototype.$User.auth().then( response => {
		next();
	}).catch( error => {
		next('/login');
	});
	
});

Vue.filter('timeLeft', function( datetime = null )
{
	if( moment(datetime).isValid() ) return moment( new Date() ).countdown( new Date( datetime ) ).toString();
	return '';
});

new Vue({
  el: '#app',
  router,
  render: h => h(App)
});
