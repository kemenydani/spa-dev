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
	
	.article-item
	{
		overflow: hidden;
		position: relative;
		height: 100%;
		
		border-radius: 4px;
		
		.header {
			overflow: hidden;
			position: relative;
			width: 100%;
			min-height: 200px;
			border-bottom: 4px solid orange;
			
			.image {
				position: absolute;
				width: 100%;
				height: 100%;
				
				background-repeat: no-repeat;
				background-position: center center;
				background-size: cover;
				
				transition: all 1s ease;
				-moz-transition: all 1s ease;
				-ms-transition: all 1s ease;
				-webkit-transition: all 1s ease;
				-o-transition: all 1s ease;
				
				&:hover {
					transform: scale(1.1);
					-moz-transform: scale(1.1);
					-webkit-transform: scale(1.1);
					-o-transform: scale(1.1);
					-ms-transform: scale(1.1); /* IE 9 */
					-ms-filter: "progid:DXImageTransform.Microsoft.Matrix(M11=1.5, M12=0, M21=0, M22=1.5, SizingMethod='auto expand')"; /* IE8 */
					filter: progid:DXImageTransform.Microsoft.Matrix(M11=1.5, M12=0, M21=0, M22=1.5, SizingMethod='auto expand'); /* IE6 and 7 */
				}
			}
		}
		
		.header:hover {
		
		}
		
		&.side-image
		{
			@supports ( display: flex )
			{
				display: flex;
				.title {
					font-size: 35px;
				}
				.header {
					min-width: 50%;
					border-bottom: none;
					border-right: 4px solid orange;
				}
				
			}
		}
		&.side-image-rev
		{
			@supports ( display: flex )
			{
				display: flex;
				flex-direction: row-reverse;
				.title {
					font-size: 35px;
				}
				.header {
					min-width: 50%;
					border-bottom: none;
					border-left: 4px solid orange;
				}
				
			}
		}
		
		.content
		{
			float: right;
			height: 100%;
			position: relative;
			padding-left: 15px;
			padding-right: 15px;
			padding-top: 15px;
			
			.info {
			
			}
			
			.title {
				color: black;
				padding: 0;
				margin: 0;
			}
			
			.teaser {
			
			}
		}
	}
	
</style>