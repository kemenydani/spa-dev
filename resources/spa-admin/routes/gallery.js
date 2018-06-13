const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/gallery/overview.vue'], () => resolve(require('../components/views/gallery/overview.vue')))
};

const Upload = resolve =>
{
	require.ensure(['../components/views/gallery/upload.vue'], () => resolve(require('../components/views/gallery/upload.vue')))
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
        },
        {
            path: 'upload/:id',
            component: Upload,
            name: 'gallery.upload',
            meta: {
              title: 'Upload Gallery Images'
            }
        }
    ]
}