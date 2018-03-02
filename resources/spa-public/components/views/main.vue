
<template>
	
	<main id="wrapper">
		
		<main-header>
			footer
		</main-header>
		
		<section id="main-content">
			<router-view></router-view>
		</section>
		
		<main-footer>
			footer
		</main-footer>
		
	</main>
	
</template>

<script>

	import MainFooter from '../../components/layout/main-footer.vue';
	import MainHeader from '../../components/layout/main-header.vue';
	
	export default {
		components: { 'main-footer' : MainFooter, 'main-header' : MainHeader },
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
