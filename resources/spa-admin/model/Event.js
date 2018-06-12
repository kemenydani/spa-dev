
import EventDataService from '../service/EventDataService';
import Model from "../core/Model";

export default class Gallery extends Model {

	constructor()
	{
		super();
		
		this.DataService = new EventDataService();
		this.DB = this.DataService.Connection;
	
		return this;
	}

	

}


