import 'babel-polyfill';

import 'vuetify/dist/vuetify.css';
import Vue from 'vue';

import Router from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import Vuetify from 'vuetify';
import VeeValidate from 'vee-validate';
import App from './App.vue';
import VueCroppie from 'vue-croppie';

Vue.use(Router);
Vue.use(Vuetify, {
	theme:
	{
		primary: "#ffa700",
		secondary: "#FFE57F",
		accent: "#ffa700",
		error: "#f44336",
		warning: "#ffeb3b",
		info: "#2196f3",
		success: "#4caf50",
	}
});
Vue.use(VeeValidate);
Vue.use(VueAxios, axios);
Vue.use(VueCroppie);

import ROUTES from './routes'
import User from './model/User';

Vue.prototype.$User = new User();

Vue.prototype.$app = new Vue({
	
});

var router =  new Router({ routes: ROUTES });


router.beforeEach( (to, from, next ) => {
	
	if( to.path === '/login') next(); // can check prototype aswell
	
	Vue.prototype.$User.auth()
		.then(  () => { next() } )
		.catch( () => { next('/login') })
});

new Vue({
  el: '#app',
  router,
  render: h => h(App)
});
