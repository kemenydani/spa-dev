const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};

const Overview = resolve =>
{
	require.ensure(['../components/views/event/overview.vue'], () => resolve(require('../components/views/event/overview.vue')))
};

export default
{
    path: '/event',
    component: Index,
    children: [
        {
            path: '/',
            component: Overview,
            name: 'event.overview',
            meta: {
                title: 'Event Overview'
            }
        }
    ]
}