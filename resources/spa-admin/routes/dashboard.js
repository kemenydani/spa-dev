const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};
const View = resolve =>
{
    require.ensure(['../components/views/dashboard/view.vue'], () => resolve(require('../components/views/dashboard/view.vue')))
};

export default
{
    path: '',
    component: Index,
    children: [
        {
            path: '',
            component: View,
            name: 'dashboard',
            meta: {
	            title: 'Dashboard',
            }
        },
        {
            path: 'dashboard',
            component: View,
            name: 'dashboard.view',
            meta: {
                title: 'Dashboard',
            }
        }
    ]
}