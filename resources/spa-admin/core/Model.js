
export default class Model
{
	static instance(){
		return new this;
	}
	
	
	all()
	{
		return this.DB.get('all')
			.then( response => response.data )
			.catch( error => error );
	}
	
	findAll( query )
	{
		return this.DB.get('findAll', { params: query, headers: {'Content-Type': 'application/json'} })
			.then( response => response.data )
			.catch( error => error );
	}
	
	findAllLike( query )
	{
		return this.DB.get('findAllLike', { params: query, headers: {'Content-Type': 'application/json'} })
			.then( response => response.data )
			.catch( error => error );
	}
	
	search( query )
	{
		return this.DB.get('search_paginate', { params: query, headers: {'Content-Type': 'application/json'} } )
			.then( response => response.data )
			.catch( error => error );
	}
	
	deleteIn( range = [] ){
		return this.DB.post('delete', { range } )
			.then( response => response.data )
			.catch( error => error );
	}
	
	activateIn( range = [] ){
		return this.DB.post('activate', { range } )
			.then( response => response.data )
			.catch( error => error );
	}
	
	deactivateIn( range = [] ){
		return this.DB.post('deactivate', { range } )
			.then( response => response.data )
			.catch( error => error );
	}
	
	store( obj ){
		return this.DB.post('store', obj )
			.then( response => response.data )
			.catch( error => error );
	}
	
}
