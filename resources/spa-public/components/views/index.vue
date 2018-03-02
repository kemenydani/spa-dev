<template>
	<router-view class="view-content" ></router-view>
</template>

<script>
	export default {
		name: 'base-index',
		props: {
		
		},
		data() {
			return {
				data_field: 'data_field'
			}
		},
		beforeCreate(){
		
		},
		mounted() {
			console.log('new component mounted');
		}
	}
</script>

<style lang="scss">

</style>