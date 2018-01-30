<template>
	<div>
		role overview
		<router-link is="v-btn" :to="{ name: 'article.create' }"
				fab
				bottom
				right
				color="red"
				dark
				fixed
		>
			<v-icon>add</v-icon>
		</router-link>
	</div>
</template>

<script>
	
	export default {
		name: 'role-overview',
		data() {
			return {
				data_field: 'data_field'
			}
		},
		mounted() {
			console.log('new component mounted');
		}
	}
</script>

<style lang="scss">

</style>