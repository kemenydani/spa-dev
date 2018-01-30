const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/settings/overview.vue'], () => resolve(require('../components/views/settings/overview.vue')))
};

export default
{
    path: '/settings',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'settings.overview',
            meta: {
                title: 'Page Settings'
            }
        }
    ]
}