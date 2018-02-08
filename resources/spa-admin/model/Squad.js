
import SquadDataService from '../service/SquadDataService';
import Model from "../core/Model";

export default class Squad extends Model {

	constructor()
	{
		super();
		
		this.DataService = new SquadDataService();
		this.DB = this.DataService.Connection;
	
		this.id = null;
		
		return this;
	}
	
}
