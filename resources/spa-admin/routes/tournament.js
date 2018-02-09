const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/tournament/overview.vue'], () => resolve(require('../components/views/tournament/overview.vue')))
};

const List = resolve =>
{
	require.ensure(['../components/views/tournament/list.vue'], () => resolve(require('../components/views/tournament/list.vue')))
};

const Create = resolve =>
{
	require.ensure(['../components/views/tournament/create.vue'], () => resolve(require('../components/views/tournament/create.vue')))
};


export default
{
	path: '/tournament',
	component: Index,
	children: [
		{
			path: '/',
			component: Overview,
			name: 'tournament.overview',
			meta: {
				title: 'Tournament Overview'
			}
		},
		{
			path: 'list',
			component: List,
			name: 'tournament.list',
			meta: {
				title: 'Tournaments'
			}
		},
		{
			path: 'create',
			component: Create,
			name: 'tournament.create',
			meta: {
				title: 'Create Tournament'
			}
		},
	]
}