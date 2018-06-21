const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/squad_member/overview.vue'], () => resolve(require('../components/views/squad_member/overview.vue')))
};

export default
{
    path: '/squad_member',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'squad_member.overview',
            meta: {
                title: 'Squad Member Overview'
            }
        }
    ]
}