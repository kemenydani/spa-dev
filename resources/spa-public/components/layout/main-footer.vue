<template>
	<footer id="main-footer">
		<section class="any-content">
		
		</section>
		<section class="support-logos">
		
		</section>
		<div class="gradient-group">
			<div class="container hc" >
				<section style="display: flex; justify-content: space-between;">
					<figure class="logo">
						<img src="https://logos-download.com/wp-content/uploads/2016/06/Fnatic_logo_wordmark.png">
					</figure>
					<nav class="sitemap">
						<ul>
							<li>TITLE</li>
							<li>item</li>
							<li>item</li>
							<li>item</li>
							<li>item</li>
							<li>item</li>
						</ul>
						<ul>
							<li>TITLE</li>
							<li>item</li>
							<li>item</li>
							<li>item</li>
							<li>item</li>
						</ul>
						<ul>
							<li>TITLE</li>
							<li>item</li>
							<li>item</li>
							<li>item</li>
							<li>item</li>
							<li>item</li>
						</ul>
						<ul>
							<li>TITLE</li>
							<li>item</li>
							<li>item</li>
							<li>item</li>
						</ul>
						<ul>
							<li>TITLE</li>
							<li>item</li>
							<li>item</li>
							<li>item</li>
							<li>item</li>
							<li>item</li>
						</ul>
					</nav>
				</section>
				<section class="bottomline">
					<span>All rights reserved.</span>
					<nav>
						<a href="">Privacy Policy.</a>
						<a href="">Terms & Conditions.</a>
					</nav>
				</section>
			</div>
		</div>
	</footer>
</template>

<script>
	
	export default {
		name: 'new-component',
		data() {
			return {
				data_field: 'data_field'
			}
		},
		mounted() {
			console.log('new component mounted');
		}
	}
	
</script>

<style lang="scss">
	
	#main-footer
	{
		background: #11171A;
		.bottomline
		{
			display: flex; justify-content: space-between;
			min-height: 40px;
			padding-bottom: 25px;
			font-weight: 700;
		}
		
		.gradient-group {
			background: linear-gradient(to top, #090C0F 0%, #11171A 47%, #11171A 100%);
			border-top: 1px solid #1a2328;
			padding-top: 50px;
		}
		
		.any-content {
			min-height: 100px;
			background: #11171A;
		}
		
		.support-logos {
			min-height: 120px;
			background: #090C0F;
		}
		figure.logo
		{
			padding: 0;
			margin: 0;
			
			img
			{
				width: 100px;
				max-width: 100px;
			}
		}
		
		nav.sitemap
		{
			width: 100%;
			display: flex;
			min-height: 240px;
			justify-content: space-between;
			max-width: 80%;
			
			ul
			{
				flex: 1;
				
				li:first-child {
					color: white;
					font-weight: 700;
					margin-bottom: 4px;
				}
				
				li {
					line-height: 20px;
				}
				
				margin: 0px;
				padding: 0px;
				list-style-type: none;
			}
		}
	}
</style>