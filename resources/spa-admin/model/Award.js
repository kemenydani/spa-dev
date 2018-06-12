
import AwardDataService from '../service/AwardDataService';
import Model from "../core/Model";

export default class Award extends Model {

	constructor()
	{
		super();
		
		this.DataService = new AwardDataService();
		this.DB = this.DataService.Connection;
	
		return this;
	}

	

}


