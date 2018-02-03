const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/video/overview.vue'], () => resolve(require('../components/views/video/overview.vue')))
};

export default
{
    path: '/video',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'video.overview',
            meta: {
                title: 'Video Overview'
            }
        }
    ]
}