
import CategoryDataService from '../services/CategoryDataService';

export default class Category {

	constructor()
	{
		this.DataService = new CategoryDataService();
		this.DB = this.DataService.Connection;
	
		this.id = null;
		this.name = '';
		this.name_short = '';
		this.context = '';
		this.categories = [];
		
		return this;
	}
	
	create( props )
	{
		return this.DB.post('create', props )
			.then( response => response.data )
			.catch( error => error );
	}
	
	get( props = {} )
	{
		return this.DB.get('all', props )
			.then( response => response.data )
			.catch( error => error );
	}
	
	getAll()
	{
		return this.get();
	}
	
	delete( id )
	{
	
	}
	
	save()
	{
	
	}

	
}


