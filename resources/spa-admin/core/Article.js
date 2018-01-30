
import ArticleDataService from '../services/ArticleDataService';

export default class Article {

	constructor()
	{
		this.DataService = new ArticleDataService();
		this.DB = this.DataService.Connection;
	
		this.id = null;
		this.content = '';
		this.teaser = '';
		this.title = '';
		
		return this;
	}
	
	create( props )
	{
		this.DB.post('create', props )
			.then( response => response.data )
			.catch( error => error );
	}
	
	delete( id )
	{
	
	}
	
	save()
	{
	
	}
	
	setTitle( string )
	{
		this.title = string;
	}
	
	setTeaser( text )
	{
		this.teaser = text;
	}
	
	setContent( text )
	{
		this.content = text;
	}
	
}


