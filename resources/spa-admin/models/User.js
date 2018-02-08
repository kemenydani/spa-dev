
import UserDataService from '../services/UserDataService';
import Model from "../core/Model";

export default class User extends Model {

	constructor()
	{
		super();
		
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