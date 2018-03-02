import axios from 'axios';

export default class Connection
{
	constructor( config )
	{
		this.axiosInstance = axios.create( config );
		return this.axiosInstance;
	}
	
}