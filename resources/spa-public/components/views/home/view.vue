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
	
	
	
</style>