
import StreamDataService from '../services/StreamDataService';
import Model from "../core/Model";

export default class Stream extends Model {

	constructor()
	{
		super();
		
		this.DataService = new StreamDataService();
		this.DB = this.DataService.Connection;
	
		this.id = null;
		
		return this;
	}
	
}
