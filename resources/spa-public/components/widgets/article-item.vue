<template>
	
	<article class="article-item">
	
		<section class="header">
			<div class="image" :style="{ backgroundImage: 'url(' + model.image + ')' }"></div>
		</section>
		
		<section class="content">
			
			<div class="info"></div>
			
			<h2 v-if="model.title" class="title"> {{ model.title }} </h2>
			
			<p v-if="model.teaser" class="teaser">{{ model.teaser }} </p>
			
		</section>
		
		<div class="footer"></div>
		
	</article>
	
</template>

<script>
	
	export default {
		name: "article-item",
		props: {
			headline: {
				type: Boolean,
				required: false,
				default: () => { return false }
			},
			teaser: {
				type: Boolean,
				required: false,
				default: () => { return false }
			},
			model: {
				required: true,
				type: Object,
				default: () => {
					return {
						title: '',
						teaser: '',
						image: '',
						datePosted: '',
					}
				},
			},
		},
		data() {

		},
		methods: {
		
		},
		beforeCreate(){

		}
	}
	
</script>

<style lang="scss" scoped>
	

	
</style>