const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/product/overview.vue'], () => resolve(require('../components/views/product/overview.vue')))
};

export default
{
    path: '/product',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'product.overview',
            meta: {
                title: 'Product Overview'
            }
        }
    ]
}