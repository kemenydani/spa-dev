const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/comment/overview.vue'], () => resolve(require('../components/views/comment/overview.vue')))
};

export default
{
    path: '/comment',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'comment.overview',
            meta: {
                title: 'Comment Overview'
            }
        }
    ]
}