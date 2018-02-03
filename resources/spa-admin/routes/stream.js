const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/stream/overview.vue'], () => resolve(require('../components/views/stream/overview.vue')))
};

export default
{
    path: '/stream',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'stream.overview',
            meta: {
                title: 'Stream Overview'
            }
        }
    ]
}