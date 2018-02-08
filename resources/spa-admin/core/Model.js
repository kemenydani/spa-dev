export default class Model
{

	all()
	{
		return this.DB.get('all')
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
	
}
