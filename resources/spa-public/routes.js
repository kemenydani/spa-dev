
import homeRouteConfig from './routes/home';
import articleRouteConfig from './routes/article';
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
	        homeRouteConfig,
	        articleRouteConfig,
        ]
    },
    loginRouteConfig
];
