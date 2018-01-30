
import dashboardRouteConfig from './routes/dashboard';
import articleRouteConfig from './routes/article';
import userRouteConfig from './routes/user';
import loginRouteConfig from './routes/login';

const Main = resolve =>
{
	require.ensure( ['./components/views/main.vue'], () => resolve( require('./components/views/main.vue') ) )
};

export default [
    {
        path: '',
        component: Main,
        children: [
	        dashboardRouteConfig,
	        articleRouteConfig,
	        userRouteConfig
        ]
    },
    loginRouteConfig
];
