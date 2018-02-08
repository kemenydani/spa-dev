
import ForumDataService from '../services/ForumDataService';
import Model from "../core/Model";

export default class Forum extends Model {

	constructor()
	{
		super();
		
		this.DataService = new ForumDataService();
		this.DB = this.DataService.Connection;
	
		this.id = null;
		
		return this;
	}
	
}
