
import CategoryDataService from '../services/CategoryDataService';

export default class Category {

	constructor()
	{
		this.DataService = new CategoryDataService();
		this.DB = this.DataService.Connection;
	
		this.id = null;
		this.content = '';
		this.teaser = '';
		this.title = '';
		
		return this;
	}
	
	create( props )
	{
		return this.DB.post('create', props )
			.then( response => response.data )
			.catch( error => error );
	}
	
	delete( id )
	{
	
	}
	
	save()
	{
	
	}

	
}


