<template>
	
	<v-card>
		<v-card-title>
			<v-layout row>
				<v-flex sm2>
					<v-select v-if="allowDelete || allowActivation"
							label="Action"
							single-line
							bottom
							persistent-hint
							@input="actionSelected()"
				      v-bind:items="data.actions"
				      v-model="selectedAction"
	            :disabled="!data.selectedItems.length"
	            :hint="selectActionHint"
					></v-select>
				</v-flex>
				<v-spacer></v-spacer>
				<v-flex sm3>
					<v-text-field v-if="searchable"
							append-icon="search"
							label="Search"
							single-line
							hide-details
							@input="fetchData()"
							v-model="filter.search"
					></v-text-field>
				</v-flex>
			</v-layout>
		</v-card-title>
		<v-data-table
				v-bind:headers="headers"
				v-bind:items="data.items"
				v-bind:pagination.sync="filter.pagination"
				v-model="data.selectedItems"
				:total-items="filter.totalItems"
				:loading="data.loading"
				class="elevation-1"
				select-all
		>
			<template slot="headers" slot-scope="props">
				<tr>
					<th style="width: 50px; max-width: 50px;" v-if="selectable">
						<v-checkbox
								primary
								hide-details
								:input-value="props.all"
								:indeterminate="props.indeterminate"
								@click.native="toggleAll"
						></v-checkbox>
					</th>
					<th v-for="header in headers" :key="header.text"
							:style="{ textAlign: header.align, width: header.width }"
					    :class="['column sortable', filter.pagination.descending ? 'desc' : 'asc', header.value ===
					    filter.pagination.sortBy ? 'active' : '']"
					    @click="changeSort(header.value)"
					>
						<v-icon>arrow_upward</v-icon>
						{{ header.text }}
					</th>
				</tr>
			</template>
			
			<template slot="items" slot-scope="props">
				<tr :active="props.selected" @click="props.selected = !props.selected">
					<td v-if="selectable">
						<v-checkbox
								primary
								hide-details
								:input-value="props.selected"
						></v-checkbox>
					</td>
					
					<td :style="{ textAlign: column.align }" v-for="column in headers">{{ formatColumn(props.item, column) }}</td>
					
				</tr>
			</template>
		</v-data-table>
	</v-card>
	
</template>

<script>
	export default
	{
		name: "data-model-manager",
		props: {
			Model: {
				type: Object,
				required: true,
			},
			uniqueKey: {
				type: String,
				required: false,
				default: () => 'id'
			},
			headers: {
				type: Array,
				required: true,
			},
			searchable: {
				type: Boolean,
				required: false,
				default: () => true
			},
			allowDelete: {
				type: Boolean,
				required: false,
				default: () => true
			},
			allowActivation: {
				type: Boolean,
				required: false,
				default: () => true
			},
		},
		data () {
			return {
				data: {
					items: [],
					selectedItems: [],
					loading: true,
					actions: [
						{
							text: 'Delete',
							value: 'delete',
						},
						{
							text: 'Activate',
							value: 'activate',
						},
						{
							text: 'Deactivate',
							value: 'deactivate',
						},
					],
				},
				filter: {
					totalItems: 0,
					pagination: {},
					search: '',
				},
				selectedAction: null,
			}
		},
		computed:
		{
			selectable()
			{
				return this.allowActivation || this.allowDelete;
			},
			selectActionHint()
			{
				return (this.data.selectedItems.length > 0) ? 'Choose action for ' + this.data.selectedItems.length + ' selected items.' :
					'';
			}
		},
		methods: {
			actionSelected()
			{
				let cb = () => {
					this.selectedAction = null;
				};
				
				switch(this.selectedAction)
				{
					case 'delete'	: this.deleteSelected(cb);
						
						break;
					case 'activate'	: this.toggleActivateSelected( true, cb );
						
						break;
					case 'deactivate'	: this.toggleActivateSelected( false, cb );
						
						break;
				}
			},
			getSelectedKeys()
			{
				let ids = [];
				
				this.data.selectedItems.forEach( selectedRow => { ids.push( selectedRow[this.uniqueKey] ) } );
				
				return ids;
			},
			deleteSelected( cb )
			{
				let selectedKeys = this.getSelectedKeys();
				
				this.Model.deleteIn( selectedKeys ).then( response =>
				{
					this.fetchData();
					cb();
				})
			},
			toggleActivateSelected( val = true, cb  )
			{
				let selectedKeys = this.getSelectedKeys();
				
				if(val === true )
				{
					this.Model.activateIn( selectedKeys ).then( response =>
					{
						this.fetchData();
						cb();
					})
				}
				if(val === false )
				{
					this.Model.deactivateIn( selectedKeys ).then( response =>
					{
						this.fetchData();
						cb();
					})
				}
			},
			formatColumn( items, config ){
				if( !config.hasOwnProperty('format') ) return items[config.value];
				return config.format( items[config.value], items );
			},
			toggleAll ()
			{
				if (this.data.selectedItems.length) this.data.selectedItems = []
				else this.data.selectedItems = this.data.items.slice()
			},
			changeSort (column) {
				if (this.filter.pagination.sortBy === column) {
					this.filter.pagination.descending = !this.filter.pagination.descending;
				} else {
					this.filter.pagination.sortBy = column;
					this.filter.pagination.descending = false;
				}
			},
			fetchData()
			{
				this.getDataFromApi()
					.then(data => {
						this.data.items = data.items;
						this.filter.totalItems = data.total;
					})
			},
			getDataFromApi () {
				this.data.loading = true;
				
				return new Promise((resolve, reject) =>
				{
					this.getModelData().then( ( APIResponse ) =>
					{
						let items = APIResponse.items;
						const total = APIResponse.total;
						
						setTimeout( () =>
						{
							this.data.loading = false;
							resolve({
								items,
								total
							})
						}, 400)
					});
					
				})
			},
			getModelData()
			{
				return this.Model.search( { search: this.filter.search, filter: this.filter.pagination } ).then();
			},
		},
		mounted () {
			this.fetchData();
		},
		watch: {
			'filter.pagination': {
				handler () {
					this.fetchData();
				},
				deep: true
			}
		},
	}
</script>

<style scoped>

</style>
