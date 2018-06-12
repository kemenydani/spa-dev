
import EnemyDataService from '../service/EnemyDataService';
import Model from "../core/Model";

export default class Enemy extends Model {

	constructor()
	{
		super();
		
		this.DataService = new EnemyDataService();
		this.DB = this.DataService.Connection;
	
		return this;
	}

	

}


