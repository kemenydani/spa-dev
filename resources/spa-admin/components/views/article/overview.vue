<template>
	<v-content>
		
		article overview
		
		<v-speed-dial
				bottom
				right
				:direction="direction"
				:hover="hover"
				:transition="transition"
				fixed
				v-model="fab"
		>
			<v-btn
					slot="activator"
					color="red"
					dark
					fab
					v-model="fab"
			>
				<v-icon>dehaze</v-icon>
				<v-icon>clear_all</v-icon>
			</v-btn>
			<router-link is="v-btn" :to="{ name: 'article.list' }"
					fab
					dark
					small
					color="blue"
			>
				<v-icon>list</v-icon>
			</router-link>
			<router-link is="v-btn" :to="{ name: 'article.create' }"
					fab
					dark
					small
					color="green"
			>
				<v-icon>add</v-icon>
			</router-link>
		</v-speed-dial>
		
</v-content>
</template>

<script>
	
	export default {
		name: 'article-overview',
		data() {
			return {
				direction: "top",
				fab: true,
				fling: false,
				hover: false,
				tabs: null,
				top: false,
				right: true,
				bottom: true,
				left: false,
				transition: 'slide-y-reverse-transition',
				data_field: 'data_field',
			}
		},
		mounted() {
			console.log('new component mounted');
		}
	}
</script>

<style lang="scss">

</style>