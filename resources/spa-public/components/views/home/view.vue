<template>
	<div class="container hc">
		<div class="home-grid">
			<div class="grid-item">item 1</div>
			<div class="grid-item">item 2</div>
			<div class="grid-item">item 3</div>
			<div class="grid-item">item 4</div>
			<div class="grid-item">item 5</div>
			<div class="grid-item">item 6</div>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'home-view',
		data() {
			return {
			
			}
		}
	}
</script>

<style lang="scss">
	.home-grid {
		width: 100%;
		background: white;
		display: grid;
		
		.grid-item {
			background: black;
			color: white;
		}
	}
</style>