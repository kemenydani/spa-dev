const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/article/overview.vue'], () => resolve(require('../components/views/article/overview.vue')))
};


export default
{
    path: '/article',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'article.overview',
            meta: {
                title: 'Article Overview'
            }
        },
    ]
}