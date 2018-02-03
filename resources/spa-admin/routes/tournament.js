const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/tournament/overview.vue'], () => resolve(require('../components/views/tournament/overview.vue')))
};

export default
{
    path: '/tournament',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'tournament.overview',
            meta: {
                title: 'Tournament Overview'
            }
        }
    ]
}