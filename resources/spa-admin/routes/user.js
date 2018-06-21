const Index = resolve =>
{
	require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/user/overview.vue'], () => resolve(require('../components/views/user/overview.vue')))
};

export default
{
	path: '/user',
	component: Index,
	children: [
		{
			path: '/',
			component: Overview,
			name: 'user.overview',
			meta: {
				title: 'User Overview'
			}
		},
	]
}
