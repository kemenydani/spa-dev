
import ContextDataService from '../service/ContextDataService';
import Model from "../core/Model";

export default class Context extends Model {

	constructor()
	{
		super();
		
		this.DataService = new ContextDataService();
		this.DB = this.DataService.Connection;
		
		return this;
	}
	
}
