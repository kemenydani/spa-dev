const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/partner/overview.vue'], () => resolve(require('../components/views/partner/overview.vue')))
};

export default
{
    path: '/partner',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'partner.overview',
            meta: {
                title: 'Partner Overview'
            }
        }
    ]
}