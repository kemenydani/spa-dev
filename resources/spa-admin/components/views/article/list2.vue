<template>
	<v-content>
		
		<v-card>
			<v-card-title>
				<v-layout row>
					<v-flex sm2>
						<!--
						<v-select
								v-bind:items="[{ text: 'Delete'},{ text: 'Activate' },{ text: 'Deactivate' }]"
								v-model="e1"
								label="Select"
								single-line
								bottom
						></v-select>
						-->
					</v-flex>
					<v-spacer></v-spacer>
					<v-flex>
						<v-text-field
								append-icon="search"
								label="Search"
								single-line
								hide-details
								@input="fetchData()"
								v-model="search.title"
						></v-text-field>
					</v-flex>
				</v-layout>
			</v-card-title>
			<v-data-table
					v-bind:headers="headers"
					v-bind:items="items"
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
		</v-card>
		
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
				search: {
					title: ''
				},
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
			fetchData(){
				this.getDataFromApi()
					.then(data => {
						this.items = data.items;
						this.totalItems = data.total;
					})
			},
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
						let items = APIResponse.items;
						const total = APIResponse.total;
						
						setTimeout( () =>
						{
							this.loading = false;
							resolve({
								items,
								total
							})
						}, 400)
					});
					
				}) // promise
				
			},
		}
	}
</script>

<style lang="scss">

</style>