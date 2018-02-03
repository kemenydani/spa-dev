const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/squad/overview.vue'], () => resolve(require('../components/views/squad/overview.vue')))
};

export default
{
    path: '/squad',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'squad.overview',
            meta: {
                title: 'Squad Overview'
            }
        }
    ]
}