
import dashboardRouteConfig from './routes/dashboard';
import articleRouteConfig from './routes/article';
import userRouteConfig from './routes/user';
import loginRouteConfig from './routes/login';
import roleRouteConfig from './routes/role';
import settingsRouteConfig from './routes/settings';
import categoryRouteConfig from './routes/category';
import squadRouteConfig from './routes/squad';
import galleryRouteConfig from './routes/gallery';
import storageRouteConfig from './routes/storage';
import commentRouteConfig from './routes/comment';

import eventRouteConfig from './routes/event';
import matchRouteConfig from './routes/match';
import enemyRouteConfig from './routes/enemy';
import awardRouteConfig from './routes/award';
import productRouteConfig from './routes/product';
import paypalRouteConfig from './routes/paypal';
import partnerRouteConfig from './routes/partner';
import squadMemberRouteConfig from './routes/squadMember';

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
	        squadRouteConfig,
	        galleryRouteConfig,
	        storageRouteConfig,
	        commentRouteConfig,
	        eventRouteConfig,
					matchRouteConfig,
          enemyRouteConfig,
          awardRouteConfig,
	        productRouteConfig,
	        paypalRouteConfig,
	        partnerRouteConfig,
	        squadMemberRouteConfig,
        ]
    },
    loginRouteConfig
];
