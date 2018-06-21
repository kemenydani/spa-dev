
import PartnerDataService from '../service/PartnerDataService';
import Model from "../core/Model";

export default class Squad extends Model {

	constructor()
	{
		super();
		
		this.DataService = new PartnerDataService();
		this.DB = this.DataService.Connection;
	
		this.id = null;
		
		return this;
	}
	
}
