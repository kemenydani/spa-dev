<template>
	
	<v-card>
		<v-card-title>
			<v-layout row>
				<v-flex sm2>
					<v-select v-if="selectable && actions.length"
							label="Action"
							single-line
							bottom
							persistent-hint
							@input="actionSelected()"
				      v-bind:items="actions"
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
					
					<th v-if="rowActions" class="column" style="text-align: center; max-width: 70px; min-width: 70px;" :key="action.name" v-for="action in rowActions">
						{{ action.name }}
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
					<td class="justify-center px-0" style="text-align: center;" v-for="action in rowActions">
						<v-btn style="text-align: center;" icon class="mx-0" @click.prevent.stop="action.callback.call(this, props.item)">
							<v-icon color="teal">{{ action.icon }}</v-icon>
						</v-btn>
					</td>
				</tr>
			</template>
		</v-data-table>
		
		<v-dialog v-model="showPrompt" max-width="290">
			<v-card>
				<v-card-title class="headline">Delete selected items?</v-card-title>
				<v-card-text>It will not be possible to bring them back.</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="green darken-1" flat="flat" @click.native="showPrompt = false">No</v-btn>
					<v-btn color="green darken-1" flat="flat" @click.native="deleteSelected">Yes</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
		
	</v-card>
	
</template>

<script>
	export default
	{
		name: "data-model-manager",
		props: {
			model: {
				type: Object,
				required: true,
			},
			uniqueKey: {
				type: String,
				required: false,
				default: () => 'id'
			},
			rowActions : {
				type: Array,
				required: false,
				default: () => []
			},
			actionsAllowed : {
				type: Array,
				required: false,
				default: () => ['delete', 'activate', 'deactivate']
			},
			selectable: {
				type: Boolean,
				required: false,
				default: () => true
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
				showPrompt: false,
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
			actions(){
				return this.data.actions.filter( action => {
					return this.actionsAllowed.indexOf(action.value) > -1;
				})
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
				switch(this.selectedAction)
				{
					case 'delete'	: this.deleteSelected();
						break;
					case 'activate'	: this.toggleActivateSelected( true );
						break;
					case 'deactivate'	: this.toggleActivateSelected( false );
						break;
				}
			},
			getSelectedKeys()
			{
				let ids = [];
				
				this.data.selectedItems.forEach( selectedRow => { ids.push( selectedRow[this.uniqueKey] ) } );
				
				return ids;
			},
			propmptMessage( message )
			{
				return new Promise((resolve, reject) => {
					this.showPrompt = true;
				});
			},
			deleteSelected( )
			{
				let selectedKeys = this.getSelectedKeys();
				
				this.$app.$emit('toast', 'Deleting items...', 'info');
				this.model.deleteIn( selectedKeys ).then( () => {
					this.fetchData();
					this.$app.$emit('toast', 'Items Deleted', 'success');
				})
				.finally( () => this.selectedAction = null )
				
			},
			toggleActivateSelected( val = true )
			{
				let selectedKeys = this.getSelectedKeys();
				
				if(val === true )
				{
					this.$app.$emit('toast', 'Activating items...', 'info');
					
					this.model.activateIn( selectedKeys )
						.then( response =>
						{
							this.fetchData();
							this.$app.$emit('toast', 'Items activated', 'success');
						})
						.catch( error => {
							this.$app.$emit('toast', 'Activation failed', 'error');
						})
						.finally( () => this.selectedAction = null )
				}
				if(val === false )
				{
					this.$app.$emit('toast', 'Deactivating items...', 'info');
					
					this.model.deactivateIn( selectedKeys )
						.then( response =>
						{
							this.fetchData();
							this.$app.$emit('toast', 'Items deactivated', 'success');
						})
						.catch( error => {
							this.$app.$emit('toast', 'Deactivation failed', 'error');
						})
						.finally( () => this.selectedAction = null )
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
				this.$app.$emit('toast', 'Updating table...', 'info');
				return this.getDataFromApi()
					.then(data => {
						this.data.items = data.items;
						this.filter.totalItems = data.total;
						this.$app.$emit('toast', 'Table updated', 'success');
					})
			},
			getDataFromApi () {
				this.data.loading = true;
				
				return new Promise((resolve, reject) =>
				{
					this.getmodelData()
						.then( ( APIResponse ) =>
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
							}, 0)
						})
						.catch( error => {
							this.$app.$emit('toast', 'Database error', 'error');
						});
				})
			},
			getmodelData()
			{
				return this.model.search( { search: this.filter.search, filter: this.filter.pagination } ).then();
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
