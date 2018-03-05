<template>
	<div class="container hc home-container">
		
		<div class="home-first-row">
			<div class="grid-item">
				<article-item :model="{ title: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', teaser: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', image: 'https://img.grouponcdn.com/deal/8DDtq5XRzVnLXEUnPHPd/p2-2048x1229/v1/c700x420.jpg' }"> </article-item>
			</div>
			<div class="grid-item">
				<article-item :model="{ title: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', teaser: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', image: 'https://img.grouponcdn.com/deal/8DDtq5XRzVnLXEUnPHPd/p2-2048x1229/v1/c700x420.jpg' }"> </article-item>
			</div>
			<div class="grid-item">
				<article-item :model="{ title: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', teaser: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', image: 'https://img.grouponcdn.com/deal/8DDtq5XRzVnLXEUnPHPd/p2-2048x1229/v1/c700x420.jpg' }"> </article-item>
			</div>
		</div>
	
		<div class="home-second-row">
			<div class="grid-item">
				<article-item :model="{ title: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', teaser: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', image: 'https://img.grouponcdn.com/deal/8DDtq5XRzVnLXEUnPHPd/p2-2048x1229/v1/c700x420.jpg' }"> </article-item>
			</div>
			<div class="grid-item">
				<article-item :model="{ title: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', teaser: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', image: 'https://img.grouponcdn.com/deal/8DDtq5XRzVnLXEUnPHPd/p2-2048x1229/v1/c700x420.jpg' }"> </article-item>
			</div>
		</div>
		
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
	
	.home-container {
		background: red;
		overflow: visible;
		@supports ( display: flex ) { flex: 1; }
		height: 100%;
		min-height: 100%;
	}
	
	.home-first-row {
		@supports ( display: grid ) {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
			grid-template-rows: auto;
			grid-gap: 20px;
		}
	}
	
	.home-second-row {
		@supports ( display: grid ) {
			display: grid;
			grid-template-columns: minmax(600px, 2fr) minmax(300px, 1fr);
			grid-template-rows: auto;
			grid-gap: 20px;
		}
	}
	
	.grid-item
	{
			background: white;
	}
	
</style>