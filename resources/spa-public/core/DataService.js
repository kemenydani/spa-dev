import Connection from "./Connection";

const API = '/api/';

export default class DataService {
	
	constructor( API_BASE, OPTIONS = {} )
	{
		this.BASE = API;
		this.ENDPOINT = API + API_BASE;
		
		this.setConnection( Object.assign( { baseURL: this.ENDPOINT }, OPTIONS ) );
		
		return this;
	}
	
	setConnection( options )
	{
		this.Connection = new Connection( options );
		
		return this;
	}
	
}
