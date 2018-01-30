const Index = resolve =>
{
	require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};
const Create = resolve =>
{
	require.ensure(['../components/views/user/create.vue'], () => resolve(require('../components/views/user/create.vue')))
};

const List = resolve =>
{
	require.ensure(['../components/views/user/list.vue'], () => resolve(require('../components/views/user/list.vue')))
};

export default
{
	path: '/user',
	component: Index,
	children: [
		{
			path: '/',
			component: List,
			name: 'user.list',
			meta: {
				title: 'Users'
			}
		},
		{
			path: 'create',
			component: Create,
			name: 'user.create',
			meta: {
				title: 'Create User'
			}
		}
	]
}
