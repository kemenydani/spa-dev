const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/gallery/overview.vue'], () => resolve(require('../components/views/gallery/overview.vue')))
};

export default
{
    path: '/gallery',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'gallery.overview',
            meta: {
                title: 'Gallery Overview'
            }
        }
    ]
}