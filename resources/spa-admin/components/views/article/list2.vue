<template>
	<v-content>
		
		<div>
			<v-data-table
					v-bind:headers="headers"
					v-bind:items="items"
					v-bind:search="search"
					v-bind:pagination.sync="pagination"
					v-model="selected"
					:total-items="totalItems"
					:loading="loading"
					class="elevation-1"
					select-all
			>
				<template slot="headers" slot-scope="props">
					<tr>
						<th style="width: 50px; max-width: 50px;">
							<v-checkbox
									primary
									hide-details
									@click.native="toggleAll"
									:input-value="props.all"
									:indeterminate="props.indeterminate"
							></v-checkbox>
						</th>
						<th :style="{ textAlign: header.align, width: header.width }" v-for="header in headers" :key="header.text"
						    :class="['column sortable', pagination.descending ? 'desc' : 'asc', header.value === pagination.sortBy ? 'active' : '']"
						    @click="changeSort(header.value)"
						>
							<v-icon>arrow_upward</v-icon>
							{{ header.text }}
						</th>
					</tr>
				</template>
				
				<template slot="items" slot-scope="props">
					<tr :active="props.selected" @click="props.selected = !props.selected">
						<td>
							<v-checkbox
									primary
									hide-details
									:input-value="props.selected"
							></v-checkbox>
						</td>
						<td>{{ props.item.id }}</td>
						<td class="text-xs-left">{{ props.item.title }}</td>
						<td class="text-xs-right">
							{{ props.item.active == 1 ? 'Active' : $options.filters.timeLeft(props.item.activation_time) }}
						</td>
						<td class="text-xs-right">{{ props.item.date_created }}</td>
					</tr>
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
				selected: [],
				search: '',
				totalItems: 0,
				items: [],
				loading: true,
				pagination: {},
				headers: [
					{ text: 'Id', align: 'left', sortable: true, value: 'title', width: '40px'},
					{ text: 'Title', value: 'title', sortable: true, align: 'left' },
					{ text: 'Activated', value: 'active', sortable: true, align: 'right', width: '200px' },
					{ text: 'Created At', value: 'date_created', sortable: true, align: 'right', width: '200px' },
				]
			}
		},
		watch: {
			pagination: {
				handler () {
					this.getDataFromApi()
						.then(data => {
							this.items = data.items;
							this.totalItems = data.total;
						})
				},
				deep: true
			}
		},
		mounted () {
			this.getDataFromApi()
				.then(data => {
					this.items = data.items;
					this.totalItems = data.total;
				})
		},
		methods: {
			toggleAll ()
			{
				if (this.selected.length) this.selected = []
				else this.selected = this.items.slice()
			},
			changeSort (column) {
				if (this.pagination.sortBy === column) {
					this.pagination.descending = !this.pagination.descending;
				} else {
					this.pagination.sortBy = column;
					this.pagination.descending = false;
				}
			},
			getArticles ( )
			{
				return (new Article()).search( { search: this.search, filter: this.pagination } ).then();
			},
			getDataFromApi () {
				this.loading = true
				
				return new Promise((resolve, reject) =>
				{
					this.getArticles().then( ( APIResponse ) =>
					{
						const { sortBy, descending, page, rowsPerPage } = this.pagination
						console.log(APIResponse.items.length)
						let items = APIResponse.items;
						const total = APIResponse.total;
						
						if (this.pagination.sortBy) {
							items = items.sort((a, b) => {
								const sortA = a[sortBy]
								const sortB = b[sortBy]
								if (descending) {
									if (sortA < sortB) return 1
									if (sortA > sortB) return -1
									return 0
								} else {
									if (sortA < sortB) return -1
									if (sortA > sortB) return 1
									return 0
								}
							})
						}
						if (rowsPerPage > 0) {
							//items = items.slice((page - 1) * rowsPerPage, page * rowsPerPage)
						}
						setTimeout(() => {
							this.loading = false
							resolve({
								items,
								total
							})
						}, 1000)
					});
					
				}) // promise
				
			},
			getDesserts () {
				return [
					{
						value: false,
						name: 'Frozen Yogurt',
						calories: 159,
						fat: 6.0,
						carbs: 24,
						protein: 4.0,
						sodium: 87,
						calcium: '14%',
						iron: '1%'
					},
					{
						value: false,
						name: 'Ice cream sandwich',
						calories: 237,
						fat: 9.0,
						carbs: 37,
						protein: 4.3,
						sodium: 129,
						calcium: '8%',
						iron: '1%'
					},
					{
						value: false,
						name: 'Eclair',
						calories: 262,
						fat: 16.0,
						carbs: 23,
						protein: 6.0,
						sodium: 337,
						calcium: '6%',
						iron: '7%'
					},
					{
						value: false,
						name: 'Cupcake',
						calories: 305,
						fat: 3.7,
						carbs: 67,
						protein: 4.3,
						sodium: 413,
						calcium: '3%',
						iron: '8%'
					},
					{
						value: false,
						name: 'Gingerbread',
						calories: 356,
						fat: 16.0,
						carbs: 49,
						protein: 3.9,
						sodium: 327,
						calcium: '7%',
						iron: '16%'
					},
					{
						value: false,
						name: 'Jelly bean',
						calories: 375,
						fat: 0.0,
						carbs: 94,
						protein: 0.0,
						sodium: 50,
						calcium: '0%',
						iron: '0%'
					},
					{
						value: false,
						name: 'Lollipop',
						calories: 392,
						fat: 0.2,
						carbs: 98,
						protein: 0,
						sodium: 38,
						calcium: '0%',
						iron: '2%'
					},
					{
						value: false,
						name: 'Honeycomb',
						calories: 408,
						fat: 3.2,
						carbs: 87,
						protein: 6.5,
						sodium: 562,
						calcium: '0%',
						iron: '45%'
					},
					{
						value: false,
						name: 'Donut',
						calories: 452,
						fat: 25.0,
						carbs: 51,
						protein: 4.9,
						sodium: 326,
						calcium: '2%',
						iron: '22%'
					},
					{
						value: false,
						name: 'KitKat',
						calories: 518,
						fat: 26.0,
						carbs: 65,
						protein: 7,
						sodium: 54,
						calcium: '12%',
						iron: '6%'
					}
				]
			}
		}
	}
</script>

<style lang="scss">

</style>