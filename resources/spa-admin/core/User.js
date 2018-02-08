
import UserDataService from '../services/UserDataService';

export default class User {

	constructor()
	{
		this.DataService = new UserDataService();
		this.DB = this.DataService.Connection;
		this.logged = false;
		this.data = {};
		
		return this;
	}
	
	login( user, password, remember )
	{
		return this.DB.post('auth', { user, password, remember }).then( response => response.data )
			       .then( data =>
			       {
			       	    this.logged = true;
			       	    this.data   = data;
			       	    
			       	    return data;
			       })
			       .catch( () => { this.logged = false } );
	}
	
	all( )
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
	
	destroy()
	{
		this.data = {};
		this.logged = false;
	}
	
	auth()
	{
		return this.DB
			.get('auth')
			.then( response => {
				//if( !response || response.status !== 200 ) return false;
				return response.data;
			})
			.then( data =>
			{
				this.logged = true;
				this.data   = data;
				
				return data;
			})
			.catch( error => {
				this.destroy()
				throw new Error('Unauthorized');
			});
	}
	
	logout()
	{
		if( this.logged !== true ) return false;
		
		return this.DB
			.post('logout')
			.then( () => { this.destroy() } )
			.catch( () => { this.destroy() } );
	}
	
	isLogged()
	{
		return this.logged;
	}
	
	getData()
	{
		return this.data;
	}
	
	getName(){
		return this.data.username;
	}
	
	getEmail(){
		return this.data.email;
	}
	
	getId(){
		return this.data.id;
	}
	
}