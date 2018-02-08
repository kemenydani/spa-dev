const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};
const Create = resolve =>
{
    require.ensure(['../components/views/article/create.vue'], () => resolve(require('../components/views/article/create.vue')))
};

const List = resolve =>
{
	require.ensure(['../components/views/article/list.vue'], () => resolve(require('../components/views/article/list.vue')))
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
        {
            path: 'list',
            component: List,
            name: 'article.list',
            meta: {
              title: 'Articles'
            }
        },
        {
            path: 'create',
            component: Create,
            name: 'article.create',
            meta: {
                title: 'Create Article'
            }
        },
    ]
}