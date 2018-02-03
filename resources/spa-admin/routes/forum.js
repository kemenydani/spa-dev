const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/forum/overview.vue'], () => resolve(require('../components/views/forum/overview.vue')))
};

export default
{
    path: '/forum',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'forum.overview',
            meta: {
                title: 'Forum Overview'
            }
        }
    ]
}