const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const List = resolve =>
{
	require.ensure(['../components/views/article/list.vue'], () => resolve(require('../components/views/article/list.vue')))
};


export default
{
    path: '/article',
    component: Index,
    children: [
        {
            path: 'list',
            component: List,
            name: 'article.list',
            meta: {
              title: 'Articles'
            }
        },
    ]
}