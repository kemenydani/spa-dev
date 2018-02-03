const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/team/overview.vue'], () => resolve(require('../components/views/team/overview.vue')))
};

export default
{
    path: '/team',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'team.overview',
            meta: {
                title: 'Team Overview'
            }
        }
    ]
}