
<template>
	
	<main>
		
		<header>
			Header
		</header>
		
		<section>
			<router-view></router-view>
		</section>
		
		<main-footer>
			footer
		</main-footer>
		
	</main>
	
</template>

<script>

	import MainFooter from '../../components/layout/main-footer.vue';
	
	export default {
		components: { 'main-footer' : MainFooter },
		name: 'app-main',
		data () {
			return {
			
			}
		},
		methods: {

		}
	}
	
</script>

<style lang="scss">

</style>
