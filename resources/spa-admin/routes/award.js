const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/award/overview.vue'], () => resolve(require('../components/views/award/overview.vue')))
};

export default
{
    path: '/award',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'award.overview',
            meta: {
                title: 'Award Overview'
            }
        }
    ]
}