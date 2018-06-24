const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/match/overview.vue'], () => resolve(require('../components/views/match/overview.vue')))
};

export default
{
    path: '/match',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'match.overview',
            meta: {
                title: 'Match Overview'
            }
        }
    ]
}