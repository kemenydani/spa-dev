const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/storage/overview.vue'], () => resolve(require('../components/views/storage/overview.vue')))
};

export default
{
    path: '/storage',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'storage.overview',
            meta: {
                title: 'Storage Overview'
            }
        }
    ]
}