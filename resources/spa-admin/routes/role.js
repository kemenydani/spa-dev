const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/role/overview.vue'], () => resolve(require('../components/views/role/overview.vue')))
};

export default
{
    path: '/role',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'role.overview',
            meta: {
                title: 'User Roles'
            }
        }
    ]
}