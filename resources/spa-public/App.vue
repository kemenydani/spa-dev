<template>
    <router-view></router-view>
</template>

<script>
	
	export default {
		name: 'app-main',
		props: {
		
		},
		data() {
			return {
			
			}
		},
    methods: {
			
    },
    beforeCreate(){
			this.axios.get('api/build', function(data){
				console.log(data)
      })
    }
	}
</script>

<style lang="scss">

</style>