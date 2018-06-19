const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const List= resolve =>
{
	require.ensure(['../components/views/paypal/list.vue'], () => resolve(require('../components/views/paypal/list.vue')))
};

export default
{
	path: '/paypal',
	component: Index,
	children: [
		{
			path: '/',
			component: List,
			name: 'paypal.overview',
			meta: {
				title: 'PayPal History'
			}
		},
	]
}