<template>
	<v-content>
		
		<div>
			<v-data-table
					v-bind:headers="headers"
					v-bind:items="items"
					v-bind:search="search"
					v-bind:pagination.sync="pagination"
					:total-items="totalItems"
					:loading="loading"
					class="elevation-1"
			>
				<template slot="items" slot-scope="props">
					<td>{{ props.item.id }}</td>
					<td class="text-xs-right">{{ props.item.title }}</td>
				</template>
			</v-data-table>
		</div>
		
		<router-link is="v-btn" :to="{ name: 'article.create' }"
				fab
				bottom
				right
				color="red"
				dark
				fixed
		>
			<v-icon>add</v-icon>
		</router-link>
	</v-content>
</template>

<script>
	
	import Article from '../../../core/Article';
	
	export default {
		data () {
			return {
				search: '',
				totalItems: 0,
				items: [],
				loading: true,
				pagination: {},
				headers: [
					{
						text: 'Id',
						align: 'left',
						//sortable: false,
						value: 'title'
					},
					{ text: 'Title', value: 'title' },
				]
			}
		},
		watch: {
			pagination: {
				handler () {
					this.getDataFromApi()
						.then(data => {
							this.items = data.items
							this.totalItems = data.total
						})
				},
				deep: true
			}
		},
		mounted ()
		{
			this.getDataFromApi()
				.then( data => {
					this.items      = data.items;
					this.totalItems = data.total
				})
		},
		methods: {
			getDataFromApi ()
			{
				this.loading = true;
				
				return new Promise( ( resolve, reject ) =>
				{
					
					const { sortBy, descending, page, rowsPerPage } = this.pagination;
					
					this.getArticles().then( items =>
					{
						const total = items.length;
						
						if (this.pagination.sortBy)
						{
							items = items.sort( ( a, b ) =>
							{
								const sortA = a[sortBy];
								const sortB = b[sortBy];
								
								if (descending)
								{
									if (sortA < sortB) return 1;
									if (sortA > sortB) return -1;
									return 0
								}
								else
								{
									if (sortA < sortB) return -1;
									if (sortA > sortB) return 1;
									return 0
								}
							})
						}
						
						if (rowsPerPage > 0)
						{
							items = items.slice((page - 1) * rowsPerPage, page * rowsPerPage)
						}
						
						setTimeout(() =>
						{
							this.loading = false;
							
							resolve({
								items,
								total
							})
						}, 1000)
					});

				})
			},
			getArticles ()
			{
				return (new Article()).search( { search: {}, filter: this.pagination } ).then();
			}
		}
	}
</script>

<style lang="scss">

</style>