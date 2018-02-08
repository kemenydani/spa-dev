
import SeasonDataService from '../service/SeasonDataService';
import Model from "../core/Model";

export default class Season extends Model {

	constructor()
	{
		super();
		
		this.DataService = new SeasonDataService();
		this.DB = this.DataService.Connection;
	
		this.id = null;
		
		return this;
	}
	
}
