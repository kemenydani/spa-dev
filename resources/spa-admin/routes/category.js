const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/category/overview.vue'], () => resolve(require('../components/views/category/overview.vue')))
};

export default
{
    path: '/category',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'category.overview',
            meta: {
                title: 'Content Categories'
            }
        }
    ]
}