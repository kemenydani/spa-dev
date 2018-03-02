const Index = resolve =>
{
    require.ensure(['../components/views/index.vue'], () => resolve(require('../components/views/index.vue')))
};
const View = resolve =>
{
    require.ensure(['../components/views/home/view.vue'], () => resolve(require('../components/views/home/view.vue')))
};

export default
{
    path: '',
    component: Index,
    children: [
        {
            path: '',
            component: View,
            name: 'home',
            meta: {
	            title: 'Home',
            }
        },
    ]
}