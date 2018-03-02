
import CategoryDataService from '../service/CategoryDataService';
import Model from "../core/Model";

export default class Category extends Model {

	constructor()
	{
		super();
		
		this.DataService = new CategoryDataService();
		this.DB = this.DataService.Connection;
	
		this.id = null;
		this.state = {};
		
		return this;
	}
	
}
