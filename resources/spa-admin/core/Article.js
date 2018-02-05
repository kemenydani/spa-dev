
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
		this.categories = [];
		
		return this;
	}
	
	create( props )
	{
		return this.DB.post('create', props )
			.then( response => response.data )
			.catch( error => error );
	}
	
	all( )
	{
		return this.DB.get('all')
			.then( response => response.data )
			.catch( error => error );
	}
	
	search( query = {} )
	{
		return this.DB.get('search_paginate', { params: query } )
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


