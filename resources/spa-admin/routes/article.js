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
	require.ensure(['../components/views/article/list2.vue'], () => resolve(require('../components/views/article/list2.vue')))
};

export default
{
    path: '/article',
    component: Index,
    children: [
        {
            path: '/',
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
        }
    ]
}