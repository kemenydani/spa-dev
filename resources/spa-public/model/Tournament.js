
import TournamentDataService from '../service/TournamentDataService';
import Model from "../core/Model";

export default class Tournament extends Model {

	constructor()
	{
		super();
		
		this.DataService = new TournamentDataService();
		this.DB = this.DataService.Connection;
	
		this.id = null;
		
		return this;
	}
	
}
