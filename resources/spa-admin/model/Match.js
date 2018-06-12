
import MatchDataService from '../service/MatchDataService';
import Model from "../core/Model";

export default class Match extends Model {

	constructor()
	{
		super();
		
		this.DataService = new MatchDataService();
		this.DB = this.DataService.Connection;
	
		return this;
	}

	

}


