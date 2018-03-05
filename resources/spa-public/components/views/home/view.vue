<template>
	<div class="container hc home-container">
			<div class="grid-item">
				<article-item :model="{ title: 'title', teaser: 'teaser', image: 'https://img.grouponcdn.com/deal/8DDtq5XRzVnLXEUnPHPd/p2-2048x1229/v1/c700x420.jpg' }"> </article-item>
			</div>
			<div class="grid-item">item 2</div>
			<div class="grid-item">item 3</div>
			<div class="grid-item">item 4</div>
			<div class="grid-item">item 5</div>
			<div class="grid-item">item 6</div>
	</div>
</template>

<script>
	
	import ArticleItem from '../../widgets/article-item'
	
	export default {
		name: 'home-view',
		components: {
			ArticleItem
		},
		data() {
			return {
			
			}
		}
	}
</script>

<style lang="scss">
	
	.home-container
	{
		background: white;
		
		@supports ( display: flex ) { flex: 1; }
		
		height: 100%;
		min-height: 100%;
		
		@supports ( display: grid )
		{
			display: grid;
			grid-template-columns: auto auto auto;
			grid-template-rows: auto;
			grid-gap: 20px;
		}
	}
	
	.grid-item
	{
			color: white;
			background: orange;
	}
	
</style>