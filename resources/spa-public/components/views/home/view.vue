<template>
	<div class="page-view">
		<div class="container hc full-height">
			
			<div class="home-grid">
				<div class="grid-item">
					<article-item
							class="side-image" :model="{ title: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', teaser: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', image: 'https://img.grouponcdn.com/deal/8DDtq5XRzVnLXEUnPHPd/p2-2048x1229/v1/c700x420.jpg' }"> </article-item>
				</div>
				<div class="grid-item">
					<article-item :model="{ title: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', teaser: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', image: 'https://img.grouponcdn.com/deal/8DDtq5XRzVnLXEUnPHPd/p2-2048x1229/v1/c700x420.jpg' }"> </article-item>
				</div>
				<div class="grid-item">
					<article-item :model="{ title: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', teaser: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', image: 'https://img.grouponcdn.com/deal/8DDtq5XRzVnLXEUnPHPd/p2-2048x1229/v1/c700x420.jpg' }"> </article-item>
				</div>
				<div class="grid-item">
					<article-item :model="{ title: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', teaser: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', image: 'https://img.grouponcdn.com/deal/8DDtq5XRzVnLXEUnPHPd/p2-2048x1229/v1/c700x420.jpg' }"> </article-item>
				</div>
				<div class="grid-item">
					<article-item :model="{ title: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', teaser: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', image: 'https://img.grouponcdn.com/deal/8DDtq5XRzVnLXEUnPHPd/p2-2048x1229/v1/c700x420.jpg' }"> </article-item>
				</div>
				<div class="grid-item">
					<article-item :model="{ title: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', teaser: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', image: 'https://img.grouponcdn.com/deal/8DDtq5XRzVnLXEUnPHPd/p2-2048x1229/v1/c700x420.jpg' }"> </article-item>
				</div>
				<div class="grid-item">
					<article-item class="side-image-rev" :model="{ title: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', teaser: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', image: 'https://img.grouponcdn.com/deal/8DDtq5XRzVnLXEUnPHPd/p2-2048x1229/v1/c700x420.jpg' }"> </article-item>
				</div>
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
	
	.home-grid
	{
		padding-top: 10px;
		padding-bottom: 10px;
		
		@supports ( display: flex )
		{
			display: flex;
			flex-wrap: wrap;
			justify-content: space-between;
		}
		
		@supports ( display: grid )
		{
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
			grid-template-rows: auto;
			grid-gap: 20px;
			
			padding-top: 20px; /* override */
			padding-bottom: 20px; /* override */
			
			:nth-child(1) { grid-column: span 2; }
			:nth-child(7) { grid-column: span 2; }
		}
		
	}
	.grid-item
	{
		position: relative;
		overflow: hidden;
		
		margin: 10px;
		
		float: left;
		
		width: calc(33.3333% - 20px);
		max-width: calc(33.3333% - 20px);
		
		background: white;
		
		&:nth-child(1) { width: calc(66.6666% - 20px); max-width: calc(66.6666% - 20px) }
		&:nth-child(2) { width: calc(33.3333% - 20px); max-width: calc(33.3333% - 20px) }
		
		&:nth-child(6) { width: calc(33.3333% - 20px); max-width: calc(33.3333% - 20px) }
		&:nth-child(7) { width: calc(66.6666% - 20px); max-width: calc(66.6666% - 20px) }
		
		@supports ( display: flex ) { float: none; }
		
		@supports ( display: grid )
		{
			float: none;
			width: 100%; /* override */
			min-width: 100%;
			max-width: 100%; /* override */
			margin: 0;
		}
		
	}
	
</style>