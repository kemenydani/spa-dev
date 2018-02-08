
import TeamDataService from '../services/TeamDataService';
import Model from "../core/Model";

export default class Team extends Model {

	constructor()
	{
		super();
		
		this.DataService = new TeamDataService();
		this.DB = this.DataService.Connection;
	
		this.id = null;
		
		return this;
	}
	
}
