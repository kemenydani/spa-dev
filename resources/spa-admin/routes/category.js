const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const List= resolve =>
{
	require.ensure(['../components/views/category/list.vue'], () => resolve(require('../components/views/category/list.vue')))
};

export default
{
	path: '/category',
	component: Index,
	children: [
		{
			path: '/',
			component: List,
			name: 'category.overview',
			meta: {
				title: 'Category Overview'
			}
		},
	]
}