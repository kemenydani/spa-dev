const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/season/overview.vue'], () => resolve(require('../components/views/season/overview.vue')))
};

export default
{
    path: '/season',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'season.overview',
            meta: {
                title: 'Season Overview'
            }
        }
    ]
}