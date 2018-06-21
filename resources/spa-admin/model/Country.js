
import CountryDataService from '../service/CountryDataService';
import Model from "../core/Model";

export default class Country extends Model {

	constructor()
	{
		super();
		
		this.DataService = new CountryDataService();
		this.DB = this.DataService.Connection;
		
		return this;
	}
	
}
