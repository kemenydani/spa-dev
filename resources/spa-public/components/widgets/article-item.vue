<template>
	
	<article class="article-item">
	
		<section class="header" :style="{ backgroundImage: 'url(' + model.image + ')' }"></section>
		
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
	
	.article-item {
		
		.header {
			width: 100%;
			height: 170px;
			background-repeat: no-repeat;
		}
		
		.content {
			
			.info {
			
			}
			
			.title {
			
			}
			
			.teaser {
			
			}
		}
	}
	
</style>