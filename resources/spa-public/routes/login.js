const Index = resolve =>
{
    require.ensure( ['../components/views/login/index.vue'], () => resolve( require('../components/views/login/index.vue') ) )
};

export default
{
    path: '/login',
    component: Index,
		name: 'login',
}