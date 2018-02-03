
import dashboardRouteConfig from './routes/dashboard';
import articleRouteConfig from './routes/article';
import userRouteConfig from './routes/user';
import loginRouteConfig from './routes/login';
import roleRouteConfig from './routes/role';
import settingsRouteConfig from './routes/settings';
import categoryRouteConfig from './routes/category';
import teamRouteConfig from './routes/team';
import squadRouteConfig from './routes/squad';
import seasonRouteConfig from './routes/season';
import tournamentRouteConfig from './routes/tournament';
import videoRouteConfig from './routes/video';
import streamRouteConfig from './routes/stream';
import galleryRouteConfig from './routes/gallery';
import storageRouteConfig from './routes/storage';
import forumRouteConfig from './routes/forum';
import commentRouteConfig from './routes/comment';

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
	        userRouteConfig,
	        roleRouteConfig,
	        settingsRouteConfig,
	        categoryRouteConfig,
	        teamRouteConfig,
	        squadRouteConfig,
	        seasonRouteConfig,
	        tournamentRouteConfig,
	        streamRouteConfig,
	        videoRouteConfig,
	        galleryRouteConfig,
	        storageRouteConfig,
	        forumRouteConfig,
	        commentRouteConfig,
        ]
    },
    loginRouteConfig
];
