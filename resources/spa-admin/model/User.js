
import UserDataService from '../service/UserDataService';
import Model from "../core/Model";

export default class User extends Model {

	constructor()
	{
		super();
		
		this.DataService = new UserDataService();
		this.DB = this.DataService.Connection;
		this.logged = false;
		this.state = {};
		
		return this;
	}
	
	getNotifications()
	{
		if(){
			
		}
	}
	
	login( user, password, remember )
	{
		return this.DB.post('auth', { user, password, remember }).then( response => response.data )
			       .then( state =>
			       {
			       	    this.logged = true;
			       	    this.state   = state;
			       	    
			       	    return state;
			       })
			       .catch( () => { this.logged = false } );
	}
	
	destroy()
	{
		this.state = {};
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
			.then( state =>
			{
				this.logged = true;
				this.state   = state;
				
				return state;
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
		return this.state;
	}
	
	getName(){
		return this.state.username;
	}
	
	getEmail(){
		return this.state.email;
	}
	
	getId(){
		return this.state.id;
	}
	
}