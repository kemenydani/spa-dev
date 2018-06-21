
import SquadMemberDataService from '../service/SquadMemberDataService';
import Model from "../core/Model";

export default class SquadMember extends Model {

	constructor()
	{
		super();
		
		this.DataService = new SquadMemberDataService();
		this.DB = this.DataService.Connection;
	
		this.id = null;
		
		return this;
	}
	
}
