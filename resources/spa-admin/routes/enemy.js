const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/enemy/overview.vue'], () => resolve(require('../components/views/enemy/overview.vue')))
};

export default
{
    path: '/enemy',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'enemy.overview',
            meta: {
                title: 'Gallery Overview'
            }
        }
    ]
}